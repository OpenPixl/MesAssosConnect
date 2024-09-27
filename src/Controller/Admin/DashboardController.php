<?php

namespace App\Controller\Admin;

use App\Repository\Admin\AssociationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    #[Route('/admin/dashboard', name: 'mac_admin_dashboard_index')]
    public function index(AssociationRepository $associationRepository): Response
    {
        $user = $this->getUser();
        $associations = $associationRepository->findAll();

        return $this->render('admin/dashboard/index.html.twig', [
            'associations' => $associations
        ]);
    }

    // Personnalisation de la navbar
    #[Route("/admin/dashboard/menus", name:'mac_admin_dashboard_listmenus')]
    public function NavBar(Request $request): Response
    {
        // on récupère l'utilisateur courant
        $user = $this->getUser();

        // préparation des éléments d'interactivité du menu
        //$application = $applicationRepository->findFirstReccurence();

        return $this->render('admin/dashboard/include/navbar_admin.html.twig');
    }
}
