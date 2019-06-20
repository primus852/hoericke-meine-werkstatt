<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @Route("/sponsoring", name="sponsoring")
     */
    public function sponsoring()
    {
        return $this->render('default/sponsoring.html.twig', [
        ]);
    }
}
