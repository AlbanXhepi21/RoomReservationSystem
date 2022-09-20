<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class AdminController extends AbstractDashboardController
{
    #[Route('/', name: 'admin_dashboard')]
       public function index( ): Response
    {
        return $this->render('admin/homepage.html.twig', [

        ]);
    }

    #[Route('/requests', name: 'admin_requests')]
    public function requests( EntityManagerInterface $entityManager): Response
    {

        $reservations= $entityManager->getRepository(Reservation::class);
        $requests = $reservations->findBy(['reservationStatus'=>'pending']);


        return $this->render('admin/requests.html.twig', [
            'reservations'=> $reservations,
            'requests'=>$requests

        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('RoomReservationProject');
    }

    /**
     * @Route("/admin/login", name="app_admin_login")
     */
    public function view(){
        return $this->render("login.html.twig");
    }




    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
