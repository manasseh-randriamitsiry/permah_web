<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Event;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Doctrine\ORM\EntityManagerInterface;

class DashboardController extends AbstractDashboardController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // Log the user's roles for debugging
        $roles = $this->getUser() ? $this->getUser()->getRoles() : [];
        error_log('User roles: ' . implode(', ', $roles));

        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $userCount = $this->entityManager->getRepository(User::class)->count([]);
        $eventCount = $this->entityManager->getRepository(Event::class)->count([]);

        return $this->render('admin/dashboard.html.twig', [
            'userCount' => $userCount,
            'eventCount' => $eventCount,
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Event Management System')
            ->setFaviconPath('favicon.ico')
            ->renderContentMaximized();
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        
        yield MenuItem::section('Users');
        yield MenuItem::linkToCrud('Users', 'fas fa-users', User::class)
            ->setPermission('ROLE_ADMIN');
        
        yield MenuItem::section('Events');
        yield MenuItem::linkToCrud('Events', 'fas fa-calendar', Event::class);
        
        yield MenuItem::section('Links');
        yield MenuItem::linkToUrl('API Documentation', 'fas fa-book', '/api/docs')
            ->setLinkTarget('_blank');
        yield MenuItem::linkToUrl('Homepage', 'fas fa-home', '/')
            ->setLinkTarget('_blank');
    }

    public function configureCrud(): Crud
    {
        return Crud::new()
            ->setDateFormat('medium')
            ->setTimeFormat('short')
            ->setDateTimeFormat('medium')
            ->setDateIntervalFormat('%%y Year(s) %%m Month(s) %%d Day(s)')
            ->setNumberFormat('%.2d')
            ->setPaginatorPageSize(10)
            ->setPaginatorRangeSize(3)
            ->showEntityActionsInlined();
    }
}
