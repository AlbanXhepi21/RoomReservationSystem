<?php

namespace App\Form;

use App\Entity\Building;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BuildingType extends AbstractType
{


    private $userRepository;


    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('address')
            ->add('admin', EntityType::class,
                [
                    'class' => User::class,
                    'choice_label' => function(User $user) {
                      if(in_array('ROLE_ADMIN',$user->getRoles()))
                        return sprintf(' %s  %s', $user->getFirstName(), $user->getLastName()) ;
                    },
                    'placeholder' => 'Choose an admin',
                    'choices' => $this->userRepository->findAll(),])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Building::class,
        ]);
    }
}
