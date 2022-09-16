<?php

namespace App\Controller;

use App\Form\RoomType;
use App\Repository\RoomRepository;
use App\Entity\Room;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RoomController extends BaseController
{

    #[Route('/room/show', name: 'app_room_index')]
    public function index(RoomRepository $roomRepository): Response
    {
        $room = $roomRepository->findOneBy(['id' => '1']);
        return $this->render('room/show.html.twig',
            ['room' => $room]);
    }


    #[Route('/room/new', name: 'app_room_new', methods: ['GET', 'POST'])]
    public function new(Request $request, RoomRepository $roomRepository): Response
    {
        $room = new Room();
        $form = $this->createForm(RoomType::class, $room);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $roomRepository->add($room, true);

            return $this->redirectToRoute('app_room_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('room/new.html.twig', [
            'room' => $room,
            'form' => $form,
        ]);
    }

    #[Route('/room/{id}', name: 'app_room_show', methods: ['GET'])]
    public function show(Room $room): Response
    {
        return $this->render('room/show.html.twig', [
            'room' => $room,
        ]);
    }

    #[Route('/room/{id}/edit', name: 'app_room_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Room $room, RoomRepository $roomRepository): Response
    {
        $form = $this->createForm(RoomType::class, $room);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $roomRepository->add($room, true);

            return $this->redirectToRoute('app_room_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('room/edit.html.twig', [
            'room' => $room,
            'form' => $form,
        ]);
    }

    #[Route('/room/{id}', name: 'app_room_delete', methods: ['POST'])]
    public function delete(Request $request, Room $room, RoomRepository $roomRepository): Response
    {
        /*if ($this->isCsrfTokenValid('delete'.$room->getId(), $request->request->get('_token'))) {
            $roomRepository->remove($room, true);
        }
            */
        return $this->redirectToRoute('app_room_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/', name: 'app_homepage')]
    public function homepage(){
        return $this->render('homepage.html.twig');
    }





}

