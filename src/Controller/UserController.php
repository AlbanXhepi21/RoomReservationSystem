<?php

namespace App\Controller;

use App\Entity\Building;
use App\Entity\Reservation;
use App\Entity\Room;
use App\Entity\User;
use App\Form\AskForReservationType;
use App\Form\UserRegistrationFormType;
use App\Repository\RoomRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/user')]
class UserController extends BaseController
{

    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/personnelHomepage.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }



    #[Route('/ask', name: "app_reservation_ask")]
    public function ask(Request $request){

        $form = $this->createForm(AskForReservationType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('app_book_ask', [
                'request' => $request
            ], 307);
        }

        return $this->renderForm('user/ask_form.html.twig', [
            'askForm' => $form
        ]);
    }

    #[Route('/book/ask', name:'app_book_ask', methods: ['GET','POST'])]
    public function resultOfForm(Request $request, RoomRepository $roomRepository):Response
    {
        $askedRecord = $request->get('ask_for_reservation');
        $askedCapacity = $askedRecord['capacity'];
        $askedBuilding = $askedRecord['building'];
        $rooms = $roomRepository->findByCapacityBuilding($askedCapacity, $askedBuilding);
        return $this->render('user/rooms.html.twig',
            ['rooms' => $rooms]);

    }


    #[Route('/book', name: 'app_book_user')]
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

        $reservationsA= $allReservations->findBy(['reservationStatus'=>'approved', 'date'=>$date,'room'=>$room]);
        $reservations=$this->getUpdatedStatus($reservationsA);

        $userReservation= $allReservations->findBy(['user'=>$this->getUser(),'date'=>$date,'room'=>$room]);



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


