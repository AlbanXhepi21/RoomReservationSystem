<?php

namespace App\Controller;


use App\Entity\Reservation;
use App\Entity\User;
use App\Form\UserRegistrationFormType;
use App\Repository\BuildingRepository;
use App\Repository\RoomRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route('/admin')]
class AdminController extends BaseController
{
    #[Route('/', name: 'app_admin_dashboard')]
    public function index(BuildingRepository $buildingRepository, RoomRepository $roomRepository){

        $buildings = $buildingRepository->findAll();
        $rooms = $roomRepository->findAll();

        return $this->render('/admin/index.html.twig', [
            'buildings'=> $buildings,
            'rooms' => $rooms
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
            $requestedReservations[$key]=$this->merge($approvedReservations[$key],$request->getStatus());
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


            foreach($requests as $key=>$value)
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


        return $result;
    }


    #[Route('/user/{id}', name: 'app_user_show', methods: ['GET'], requirements: ['id' => '\d+']), IsGranted('ROLE_ADMIN')]
    public function show(int $id, Request $request, UserRepository $userRepository): Response
    {
        $user = $userRepository->findOneBy(['id'=>$id]);
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/user/', name: 'app_users_show', methods: ['GET'])]
    public function view(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }


    #[Route('/user/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+']), IsGranted('ROLE_ADMIN')]
    public function edit(int $id, Request $request, UserRepository $userRepository): Response
    {
        $user = $userRepository->findOneBy(['id'=>$id]);
        $form = $this->createForm(UserRegistrationFormType::class,$user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->add($user, true);

            return $this->redirectToRoute('app_user_index',[], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/user/{id}', name: 'app_user_delete', methods: ['POST'], requirements: ['id' => '\d+'] ), IsGranted('ROLE_ADMIN')]
    public function delete(int $id, Request $request, UserRepository $userRepository): Response
    {
        $user = $userRepository->findOneBy(['id'=>$id]);
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/user/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserRegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($passwordHasher->hashPassword($user,$form['plainPassword']->getData()));
            if (true === $form['agreeTerms']->getData()) {
                $user->agreeTerms();
            }

            $userRepository->add($user, true);

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

}
