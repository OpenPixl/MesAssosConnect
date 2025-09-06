<?php

namespace App\Controller\Easy;

use App\Entity\Admin\Member;
use App\Entity\Gestion\adhesions\Adherent;
use App\Entity\Gestion\Associations\Association;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/mac_admin', name: 'mac_administration_dashboard')]
    public function index(): Response
    {
        //return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(MemberCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        return $this->render('@EasyAdmin/page/content.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('MesAssosConnect Admin');
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linkToDashboard('Dashboard', 'fa fa-home'),

            MenuItem::section('ESPACE Associations'),
            MenuItem::linkToCrud('Utilisateurs de MAC', 'fa fa-list', Member::class),
            MenuItem::linkToCrud('assos de MAC', 'fa fa-list', Association::class),
            MenuItem::linkToCrud('Adhésions', 'fa fa-list', Adherent::class),
            MenuItem::section('GESTIONS'),
            MenuItem::linkToUrl('Accès API', null, '/docs'),
        ];
    }
}
