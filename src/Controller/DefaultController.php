<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default")
     */
    public function index()
    {
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

    /**
     * @Route("/leistungen", name="leistungen")
     */
    public function leistungen()
    {
        return $this->render('default/leistungen.html.twig', [

        ]);
    }

    /**
     * @Route("/werkstatt", name="werkstatt")
     */
    public function werkstatt()
    {
        return $this->render('default/werkstatt.html.twig', [

        ]);
    }

    /**
     * @Route("/sponsoring", name="sponsoring")
     */
    public function sponsoring()
    {
        return $this->render('default/sponsoring.html.twig', [
        ]);
    }

    /**
     * @Route("/geschichte", name="geschichte")
     */
    public function geschichte()
    {
        return $this->render('default/geschichte.html.twig', [
        ]);
    }

    /**
     * @Route("/kontakt", name="kontakt")
     */
    public function kontakt()
    {
        return $this->render('default/kontakt.html.twig', [
        ]);
    }

    /**
     * @Route("/impressum", name="impressum")
     */
    public function impressum()
    {
        return $this->render('default/impressum.html.twig', [
        ]);
    }

    /**
     * @Route("/reifenwechsel", name="reifen")
     */
    public function reifen()
    {
        return $this->render('default/reifen.html.twig', [
        ]);
    }

    /**
     * @Route("/sitemap.xml", name="sitemap")
     */
    public function sitemapAction()
    {

        $response = new Response();
        $response->headers->set('Content-Type', 'application/xml; charset=utf-8');
        return $this->render(
            'xml/sitemap.xml.twig',
            array(),
            $response
        );

    }
}
