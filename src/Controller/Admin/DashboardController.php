<?php

namespace App\Controller\Admin;

use App\Entity\News;
use App\Entity\Enclosure;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use Symfony\Component\HttpFoundation\RedirectResponse;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/", name="admin")
     */
    public function index(): Response
    {
        $routeBuilder = $this->get(CrudUrlGenerator::class)->build();
        $url = $routeBuilder->setController(NewsCrudController::class)->generateUrl();

        return $this->redirect($url."&menuIndex=0");
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Hismith Test');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToCrud('News', 'fas fa-newspaper', News::class);
        yield MenuItem::linkToCrud('Enclosures', 'fas fa-image', Enclosure::class);
    }
}
