<?php

namespace App\Controller\Webapp;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PublicController extends AbstractController
{
    #[Route('/public', name: 'mac_webapp_public')]
    public function index(): Response
    {
        return $this->render('webapp/public/index.html.twig');
    }
}
