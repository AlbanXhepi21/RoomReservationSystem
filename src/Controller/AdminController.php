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
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

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

        $requestedReservations=[];
        $approvedReservations = [];
        /** @var Reservation $request */
        foreach($requests as $key=>$request)
            {
                $reserved = $reservations->findBy(['reservationStatus'=>'approved', 'date'=>$request->getDate(),'room'=>$request->getRoom()]);

               $approvedReservations[$key]=$this->getUpdatedStatus($reserved);

            }

        foreach($requests as $key=>$request)
        {
            $requestedReservations[$key]=$this->merge($approvedReservations[$key],$requests);
        }









        return $this->render('admin/requests.html.twig', [
            'reservations'=> $reservations,
            'requests'=>$requests,
            'requestedReservations' =>  $requestedReservations

        ]);




    }

    public function getUpdatedStatus(array $reservations): array
    {
        $result=[1,1,1,1,1,1,1];
        /** @var Reservation $reservation */
        foreach($reservations as $reservation)
        {
            $status=$reservation->getStatus();
            foreach($status as $key=>$value)
            {
                if($result[$key]!= $value && $result[$key]==1)
                {

                    $result[$key]=$value;
                }
            }
        }

        return $result;
    }

    public function merge(array $reservations, array $requests): array
    {
        $result=[1,1,1,1,1,1,1];
        /** @var Reservation $reservation */
        foreach($requests as $i=>$request)
        {
            $status=$request->getStatus();
            foreach($status as $key=>$value)
            {

               if(($reservations[$key]===0  && $value===1 )  )
                {
                    $result[$key]=3;

                }

                elseif($reservations[$key]===1  && $value===0)
                {
                    $result[$key]=0;
                }


            }
        }

        return $result;
    }



    /**
     * @Route("/admin/login", name="app_admin_login")
     */
    public function view(AuthenticationUtils $authenticationUtils){
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render("security/login.html.twig", ['last_username' => $lastUsername, 'error' => $error]);
    }

}
