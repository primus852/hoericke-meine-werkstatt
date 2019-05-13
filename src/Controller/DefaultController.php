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
     * @Route("/leistungen2", name="leistungen2")
     */
    public function leistungen2()
    {
        return $this->render('default/leistungen2.html.twig', [
        ]);
    }
}
