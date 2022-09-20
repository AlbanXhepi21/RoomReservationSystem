<?php

namespace App\Controller;

use App\Repository\BuildingRepository;
use App\Repository\RoomRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AdminController extends BaseController
{
    #[Route('/admin', name: 'admin')]
    public function index(BuildingRepository $buildingRepository, RoomRepository $roomRepository){

        $buildings = $buildingRepository->findAll();
        $rooms = $roomRepository->findAll();

         return $this->render('admin/index.twig.html', [
             'buildings'=> $buildings,
             'rooms' => $rooms
         ]);
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
