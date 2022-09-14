<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;

class RoomController extends BaseController
{
    /**
     * @Route("/rooms/view", name="app_rooms_view")
     */
    public function view(){
        return $this->render('rooms/view.html.twig');
    }

    /**
     * @Route("/",name="app_homepage")
     */
    public function viewHomepage(){
        return $this->render('homepage.html.twig');
    }

}