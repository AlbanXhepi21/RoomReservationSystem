<?php

namespace App\Controller;

use App\Entity\Building;
use App\Entity\Reservation;
use App\Entity\Room;
use App\Entity\User;
use App\Form\UserRegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Symfony\Component\String\u;

#[Route('/user')]
class UserController extends BaseController
{

    #[Route('/index', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/personnelHomepage.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/show', name: 'app_users_show', methods: ['GET'])]
    public function show(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository): Response
    {
        $user = new User();
        $form = $this->createForm(UserRegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->add($user, true);

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/book')]
    public function book(EntityManagerInterface $entityManager):Response
    {
        $building= $entityManager->getRepository(Building::class);
        $buildings = $building->findAll();

        return $this->render('user/book.html.twig', [
            'buildings' => $buildings
        ]);
    }

    #[Route('/book/{slug}')]
    public function singleBook($slug ,EntityManagerInterface $entityManager):Response
    {
        $buildingsRepo= $entityManager->getRepository(Building::class);
        /** @var Building $building */
        $building = $buildingsRepo->find($slug);

        $rooms = $building->getRooms();


        return $this->render('user/rooms.html.twig', [
            'rooms' => $rooms
        ]);

    }

    #[Route('/book/{slug}/{room}' , name: 'single_room')]
    public function singleRoom($slug, $room ,EntityManagerInterface $entityManager,Request $request):Response
    {

        $roomsRep = $entityManager->getRepository(Room::class);
        $room = $roomsRep->find($room);

        $allReservations = $entityManager->getRepository(Reservation::class);


        $now=new \DateTime();

        $date= $request->query->get('date' ,$now->format('Y-m-d'));
        $date=date_create_from_format('Y-m-d',$date);

        $reservationsA= $allReservations->findBy(['reservationStatus'=>'approved', 'date'=>$date]);
        $reservations=$this->getUpdatedStatus($reservationsA);

        $userReservation= $allReservations->findBy(['user'=>$this->getUser()]);



        $newStatus= $request->query->get('status_array' ,null);

        $intArray = array_map(
            function($value) { return (int)$value; },
            explode(',',$newStatus)
        );
        $repeat=true;

        foreach ($userReservation as $singleReservation)
            {

                if($singleReservation->getStatus()===$intArray)
                {
                    $repeat=false;
                }
            }

            if($newStatus != null && explode(',',$newStatus) != [1,1,1,1,1,1,1]  && $repeat)
            {



                $newReservation = new Reservation();


                $newReservation->setStatus($intArray);
                $newReservation->setDate($date);
                $newReservation->setRoom($room);
                $newReservation->setUser($this->getUser());



                $entityManager->persist($newReservation);
                $entityManager->flush();
            }









        return $this->render('user/singleRoom.html.twig', [
            'room' =>  $room,
            'reservations' => $reservations,
            'date' =>$date,
            'slug' =>$slug


        ]);




    }



    #[Route('/states')]
    public function userState( ):Response
    {

        $title = 'Personnel States Page, All States';

        return $this->render('user/userAllStates.html.twig', [
            'state' => $title
        ]);

    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function view(int $id, Request $request, UserRepository $userRepository): Response
    {
        $user = $userRepository->findOneBy(['id'=>$id]);
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function edit(int $id, Request $request, UserRepository $userRepository): Response
    {
        $user = $userRepository->findOneBy(['id'=>$id]);
        $form = $this->createForm(UserRegistrationFormType::class);
        if(!in_array("ROLE_ADMIN",$user->getRoles())) $form->remove('roleAdmin');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->add($user, true);

            return $this->redirectToRoute('app_user_index',[], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('security/register.html.twig', [
            'user' => $user,
            'registrationForm' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'], requirements: ['id' => '\d+'] )]
    public function delete(int $id, Request $request, UserRepository $userRepository): Response
    {
        $user = $userRepository->findOneBy(['id'=>$id]);
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @param $status array|Reservation[]
     * @return int[]
     */
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

}


