<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/villes', name: 'villes')]
    public function villes(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    #[Route('/campus', name: 'campus')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }
}
