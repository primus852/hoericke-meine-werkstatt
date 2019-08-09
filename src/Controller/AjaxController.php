<?php

namespace App\Controller;

use App\Entity\Voucher;
use Doctrine\Common\Persistence\ObjectManager;
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
     * @Route("/_ajax/_toggleVoucher", name="ajaxToggleVoucher")
     * @param Request $request
     * @param ObjectManager $em
     * @return JsonResponse
     */
    public function ajaxToggleVoucher(Request $request, ObjectManager $em)
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

        $message = (new Swift_Message('Neue Kontaktaufnahme TEST'))
            ->setFrom(getenv('MAILER_USERNAME'))
            ->setTo('tw@mitscom.de')
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
                        'message' => $nachricht
                    )
                ),
                'text/html'
            );

        $mailer->send($message);

        return ShortResponse::success('Vielen Dank für Ihre Kontaktaufnahme, wir melden uns umgehend bei Ihnen');

    }

}
