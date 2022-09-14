<?php

namespace App\Controller;

use App\Repository\RoomRepository;
use Symfony\Component\Routing\Annotation\Route;

class RoomController extends BaseController
{
    /**
     * @Route("/rooms/view", name="app_rooms_view")
     */
    public function view(RoomRepository $roomRepository, ){

        $rooms = $roomRepository->findAll();
        return $this->render('rooms/view.html.twig',
        [ 'rooms'=> $rooms]);
    }

    /**
     * @Route("/",name="app_homepage")
     */
    public function viewHomepage(){
        return $this->render('homepage.html.twig');
    }

}