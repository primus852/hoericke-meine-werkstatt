<?php

namespace App\Command;

use BackupManager\Filesystems\Destination;
use BackupManager\Manager;
use DateTime;
use Exception;
use Swift_Mailer;
use Swift_Message;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class MakeBackupCommand extends Command
{
    protected static $defaultName = 'app:make-backup';
    private $bManager;
    private $mailer;
    private $twig;
    private $project;

    /**
     * See services.yaml
     */
    private $bFolder;
    private $bHost;
    private $bPort;
    private $bUser;
    private $bPass;
    private $bRoot;
    private $bSsl;

    /**
     * MakeBackupCommand constructor.
     * @param Manager $bManager
     * @param Environment $twig
     * @param Swift_Mailer $mailer
     * @param string $bFolder
     * @param string $bHost
     * @param string $bPort
     * @param string $bUser
     * @param string $bPass
     * @param string $bRoot
     * @param string $bSsl
     */
    public function __construct(Manager $bManager, Environment $twig, Swift_Mailer $mailer, string $bFolder, string $bHost, string $bPort, string $bUser, string $bPass, string $bRoot, string $bSsl)
    {
        $this->bManager = $bManager;
        $this->mailer = $mailer;
        $this->twig = $twig;
        
        $this->bFolder = $bFolder;
        $this->bHost = $bHost;
        $this->bPort = $bPort;
        $this->bUser = $bUser;
        $this->bPass = $bPass;
        $this->bRoot = $bRoot;
        $this->bSsl = $bSsl === 'true' ? true : false;

        parent::__construct();
    }


    protected function configure()
    {
        $this
            ->setDescription('Make Backup and upload via FTP')
            ->addArgument('project', InputArgument::REQUIRED, 'Project Identifier')
            ->addArgument('debug', InputArgument::OPTIONAL, 'Enable Debug');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $debug = $input->getArgument('debug') === 'y' ? true : false;
        $this->project = $input->getArgument('project');

        $start = new DateTime();

        $debug ? $io->text('<fg=green>Starting upload</>') : null;

        try {
            $now = new DateTime();
            $filename = $this->bFolder . '_' . $now->format('Y-m-d_H-i-s');
            $this->bManager->makeBackup()->run('production', [new Destination('ftp', $filename)], 'gzip');

            $end = new DateTime();
            $intervalCode = $start->diff($end);

            /**
             * Check the Filesize of the Output file
             */
            $ftp = ftp_ssl_connect($this->bHost);
            if ($ftp) {

                $r = ftp_login($ftp, $this->bUser, $this->bPass);
                if ($r) {

                    /**
                     * Change the directory
                     */
                    if(ftp_chdir($ftp, $this->bRoot)){
                        if(ftp_chdir($ftp, $this->bFolder)){

                            /**
                             * Get Size of Backup
                             */
                            $size = ftp_size($ftp, $filename . '.gz');

                            if ($size <= 0) {
                                $this->sendMail("Backup fehlgeschlagen. Fehler: FTP_CHECK_FILESIZE, Project: " . $this->project . ". Das Backup ist nicht vorhanden oder leer.");
                                $io->error('Invalid Filesize of file \'' . $this->bRoot . '/' . $this->bFolder . '/' . $filename . '.gz' . '\': ' . $size);
                            }else{

                                $this->sendMail('Backup war erfolgreich.|Start:' . $start->format('Y-m-d H:i:s') . '|End:' . $end->format('Y-m-d H:i:s') . '|Duration:' . $intervalCode->format('%i minutes and %s seconds') . '|Project:' . $this->project . '|MITSComInternalBackupScript');

                            }
                        }else{
                            $this->sendMail("Backup fehlgeschlagen. Fehler: FTP_CHECK_CHDIR, Project: " . $this->project . ". Konnte nicht in Ordner ".$this->bFolder." wechseln.");
                            $io->error('Could not change directory: '.$this->bFolder);
                        }
                    }else{
                        $this->sendMail("Backup fehlgeschlagen. Fehler: FTP_CHECK_CHDIR, Project: " . $this->project . ". Konnte nicht in Ordner ".$this->bRoot." wechseln.");
                        $io->error('Could not change directory: '.$this->bRoot);
                    }

                } else {
                    $this->sendMail("Backupüberprüfung fehlgeschlagen. Fehler: FTP_CHECK_LOGIN_FAIL, Project: " . $this->project . ". Das Backup konnte nicht auf erfolgreichen Upload überprüft werden");
                    $io->error('Could login to FTP: ' . $this->bUser);
                }

                ftp_close($ftp);

            } else {
                $this->sendMail("Backupüberprüfung fehlgeschlagen. Fehler: FTP_CHECK_CONNECT_FAIL, Project: " . $this->project . ". Das Backup konnte nicht auf erfolgreichen Upload überprüft werden");
                $io->error('Could not connect to FTP: ' . $this->bHost);
            }

            # Restore
            # $this->bManager->makeRestore()->run('ftp', 'mitswiki_' . $now->format('Y-m-d_H-i-s'), 'production', 'gzip');

        } catch (Exception $e) {
            //$this->sendMail("Backup fehlgeschlagen. Fehler: " . $e->getMessage() . ", Project: " . $this->project);
            $io->error('Exception: ' . $e->getMessage());
        }

        $debug ? $io->text('<fg=green>completed</>') : null;

    }

    /**
     * @param string $sendText
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    private function sendMail(string $sendText)
    {

        $message = (new Swift_Message('Backup - Höricke - Hoericke'))
            ->setFrom('info@hoerickemeinewerkstatt.de')
            ->setCharset('UTF-8')
            ->setTo('backups@mitscom.de')
            ->setContentType('text/html')
            ->setBody($this->twig->render('email/emailBackupStatus.html.twig', array(
                'project' => $this->project,
                'result' => $sendText,
            )));

        try {
            $this->mailer->send($message);
        } catch (Exception $e) {
            die($e->getMessage());
        }

    }
}
