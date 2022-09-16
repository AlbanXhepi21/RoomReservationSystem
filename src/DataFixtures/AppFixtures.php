<?php

namespace App\DataFixtures;

use App\Entity\Building;
use App\Entity\Room;
use App\Factory\RoomFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager,  ): void
    {

         ;
        UserFactory::createMany(10);

        $buildings = $manager->getRepository(Building::class);

        $building= $buildings->findBy(['id'=>'1']);



//
//        $room1 = new Room();
//
//
//        $room1->setCapacity(10);
//        $room1->setName("A32");
//        $room1->setStatus([1,1,1,1,1,1,1]);
//    //        $room1->setBuilding($building[0]);
//
//
//        $room2 = new Room();
//
//
//        $room1->setCapacity(10);
//        $room1->setName("A31");
//        $room1->setStatus([1,1,1,1,1,1,1]);
////        $room2->setBuilding($building[0]);
//
//
//
//
//
//
//        // $product = new Product();
//        $manager->persist($room1);
//        $manager->persist($room2);


        $manager->flush();
    }
}
