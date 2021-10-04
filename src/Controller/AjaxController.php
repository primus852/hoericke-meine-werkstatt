<?php

namespace App\Controller;

use App\Entity\SeasonSettings;
use App\Entity\Voucher;
use Doctrine\ORM\EntityManagerInterface;
use primus852\ShortResponse\ShortResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Swift_Mailer;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AjaxController extends AbstractController
{

    /**
     * @Security("is_granted('ROLE_USER')")
     * @Route("/_ajax/_toggleBanner", name="ajaxToggleBanner")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    public function ajaxToggleBanner(Request $request, EntityManagerInterface $em)
    {
        /**
         * Settings
         */
        $settings = $em->getRepository(SeasonSettings::class)->find(1);
        if ($settings === null) {
            return ShortResponse::error('Settings nicht vorhanden');
        }

        $value = $request->get('banner');
        /**
         * NEEDS VALIDATION!
         */
        $settings->setCurrentBanner($value);

        $em->persist($settings);

        try {
            $em->flush();
        } catch (\Exception $e) {
            return ShortResponse::mysql();
        }

        switch ($settings->getCurrentBanner()) {
            case 'som':
                $enabledBanner = 'Sommer';
                break;
            case 'fru':
                $enabledBanner = 'Frühling';
                break;
            case 'her':
                $enabledBanner = 'Herbst';
                break;
            case 'win':
                $enabledBanner = 'Winter';
                break;
            default:
                $enabledBanner = 'Herbst';
        }

        return ShortResponse::success('Banner aktualisiert', array(
            'status' => $enabledBanner,
        ));


    }


    /**
     * @Security("is_granted('ROLE_USER')")
     * @Route("/_ajax/_toggleRad", name="ajaxToggleRad")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    public function ajaxToggleRad(Request $request, EntityManagerInterface $em)
    {

        /**
         * Fields
         */
        $toSet = $request->get('toSet') === 'enable' ? 1 : 0;
        $newSet = $request->get('toSet') === 'enable' ? 'disable' : 'enable';
        $newClass = $request->get('toSet') === 'enable' ? 'danger' : 'success';
        $newIcon = $request->get('toSet') === 'enable' ? 'remove' : 'check';
        $newText = $request->get('toSet') === 'enable' ? 'deaktivieren' : 'aktivieren';
        $newStatus = $request->get('toSet') === 'enable' ? 'an' : 'aus';
        $id = $request->get('id');

        if ($id === null || $id === '') {
            return ShortResponse::error('Empty ID');
        }

        /**
         * Settings
         */
        $settings = $em->getRepository(SeasonSettings::class)->find($id);
        if ($settings === null) {
            return ShortResponse::error('Settings nicht vorhanden');
        }

        $settings->setRadwechselEnabled($toSet);
        $em->persist($settings);

        try {
            $em->flush();
        } catch (\Exception $e) {
            return ShortResponse::mysql();
        }

        return ShortResponse::success('Erfolgreich gespeichert', array(
            'set' => $newSet,
            'c' => $newClass,
            'icon' => $newIcon,
            'text' => $newText,
            'status' => $newStatus
        ));

    }

    /**
     * @Security("is_granted('ROLE_USER')")
     * @Route("/_ajax/_toggleVoucher", name="ajaxToggleVoucher")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    public function ajaxToggleVoucher(Request $request, EntityManagerInterface $em)
    {

        /**
         * Fields
         */
        $toSet = $request->get('toSet') === 'used' ? 1 : 0;
        $newSet = $request->get('toSet') === 'used' ? 'unused' : 'used';
        $newClass = $request->get('toSet') === 'used' ? 'danger' : 'success';
        $newIcon = $request->get('toSet') === 'used' ? 'remove' : 'check';
        $newText = $request->get('toSet') === 'used' ? 'eingelöst' : 'einlösen';
        $newStatus = $request->get('toSet') === 'used' ? 'Ja' : 'Nein';
        $id = $request->get('id');

        if ($id === null || $id === '') {
            return ShortResponse::error('Empty ID');
        }

        /**
         * Voucher
         */
        $voucher = $em->getRepository(Voucher::class)->find($id);
        if ($voucher === null) {
            return ShortResponse::error('Gutschein nicht vorhanden');
        }

        $voucher->setIsUsed($toSet);
        $em->persist($voucher);

        try {
            $em->flush();
        } catch (\Exception $e) {
            return ShortResponse::mysql();
        }

        return ShortResponse::success('Erfolgreich gespeichert', array(
            'set' => $newSet,
            'c' => $newClass,
            'icon' => $newIcon,
            'text' => $newText,
            'status' => $newStatus
        ));

    }

    /**
     * @Route("/_ajax/_sendForm", name="ajaxSendForm")
     * @param Request $request
     * @param Swift_Mailer $mailer
     * @return JsonResponse
     */
    public function ajaxSendForm(Request $request, Swift_Mailer $mailer)
    {

        /**
         * Fields
         */
        $name = $request->get('name');
        $email = $request->get('email');
        $telefon = $request->get('telefon');
        $anliegen = $request->get('anliegen');
        $nachricht = $request->get('nachricht');
        $wagen = $request->get('wagen');
        $termin = $request->get('termin');
        $datenschutz = $request->get('datenschutz');


        /**
         * Checks
         */
        if ($name === '' || $name === null) {
            return ShortResponse::error('Name darf nicht leer sein');
        }

        if ($email !== '' && $email !== null) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                return ShortResponse::error('Keine gültige Email-Adresse');
            }
        }

        if (($email === '' || $email === null) && ($telefon === '' || $telefon === null)) {
            return ShortResponse::error('Bitte füllen Sie entweder Email oder Telefon aus');
        }

        if ($datenschutz === 'Nein') {
            return ShortResponse::error('Bitte lesen Sie die Hinweise zur Datenverarbeitung');
        }

        $message = (new Swift_Message('Neue Kontaktaufnahme'))
            ->setFrom(getenv('MAILER_USERNAME'))
            ->setTo(getenv('MAILER_USERNAME'))
            ->setBody(
                $this->renderView(
                    'email/message.html.twig', array(
                        'name' => $name,
                        'telefon' => $telefon,
                        'email' => $email,
                        'anliegen' => $anliegen,
                        'wagen' => $wagen,
                        'termin' => $termin,
                        'datenschutz' => $datenschutz,
                        'message' => $nachricht,
                        'env' => getenv('MAILER_USERNAME'),
                    )
                ),
                'text/html'
            );

        $mailer->send($message);

        return ShortResponse::success('Vielen Dank für Ihre Kontaktaufnahme, wir melden uns umgehend bei Ihnen');

    }

}
