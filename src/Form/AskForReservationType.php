<?php

namespace App\Form;

use App\Entity\Building;
use App\Entity\Room;
use App\Repository\BuildingRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AskForReservationType extends AbstractType
{
    private $buildingRepository;
    public function __construct(BuildingRepository $buildingRepository)
    {
        $this->buildingRepository = $buildingRepository;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('building', EntityType::class,
                [
                    'class' => Building::class,
                    'choice_label' => function(Building $building) {
                        return sprintf('(%d) %s', $building->getId(), $building->getName());
                    },
                    'placeholder' => 'Choose a building',
                    'choices' => $this->buildingRepository->findAll(),
                ])
            ->add('capacity')
        ;
    }

//    public function configureOptions(OptionsResolver $resolver): void
//    {
//        $resolver->setDefaults([
//            'data_class' => null,
//        ]);
//    }
}
