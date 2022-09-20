<?php

namespace App\Controller;

use App\Entity\Building;
use App\Form\BuildingType;
use App\Repository\BuildingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/building')]
class BuildingController extends AbstractController
{
    #[Route('/', name: 'app_building_index', methods: ['GET'])]
    public function index(BuildingRepository $buildingRepository): Response
    {
        return $this->render('building/index.html.twig', [
            'buildings' => $buildingRepository->findAll(),
        ]);
    }

    #[Route('/{id}/rooms', name: 'app_building_rooms', methods: ['GET'], requirements: ['id'=>'\d+'])]
    public function viewRooms(int $id, BuildingRepository $buildingRepository): Response
    {
        $building = $buildingRepository->findOneby(['id'=>$id]);
        $rooms = $building->getRooms();
        return $this->render('room/index.html.twig', [
            'rooms' => $rooms,
        ]);
    }


    #[Route('/new', name: 'app_building_new', methods: ['GET', 'POST'])]
    public function new(Request $request, BuildingRepository $buildingRepository): Response
    {
        $building = new Building();
        $form = $this->createForm(BuildingType::class, $building);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $buildingRepository->add($building, true);

            return $this->redirectToRoute('app_building_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('building/new.html.twig', [
            'building' => $building,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_building_show', methods: ['GET'])]
    public function show(int $id, BuildingRepository $buildingRepository): Response
    {
        $building = $buildingRepository->findOneBy(['id'=>$id]);
        return $this->render('building/show.html.twig', [
            'building' => $building,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_building_edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function edit(int $id, Request $request, BuildingRepository $buildingRepository): Response
    {
        $building = $buildingRepository->findOneBy(['id'=>$id]);
        $form = $this->createForm(BuildingType::class, $building);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $buildingRepository->add($building, true);

            return $this->redirectToRoute('app_building_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('building/edit.html.twig', [
            'building' => $building,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_building_delete', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function delete(int $id, Request $request,BuildingRepository $buildingRepository): Response
    {
        $building = $buildingRepository->findOneBy(['id'=>$id]);
      if ($this->isCsrfTokenValid('delete'.$id, $request->request->get('_token'))) {
            $buildingRepository->remove($building,flush:true);
        }

        return $this->redirectToRoute('app_building_index', [], Response::HTTP_SEE_OTHER);
    }
}
