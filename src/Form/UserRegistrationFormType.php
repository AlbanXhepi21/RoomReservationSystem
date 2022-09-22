<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserRegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('firstName')
            ->add('lastName')
            ->add('Roles', ChoiceType::class, [
                'required' => true,
                'multiple' => false,
                'expanded' => false,
                'choices' => [
                    'User' => 'ROLE_USER',
                    'Admin' => 'ROLE_ADMIN',
                ],
            ])
            ->add('plainPassword', null, [
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Choose a password to be validated'
                    ]),
                    new Length([
                        'min' => 5,
                        'minMessage' => 'Come on, you can find another password longer than that length'
                    ])
                ]
            ])
            ->add('agreeTerms', CheckboxType::class,
                [
                    'mapped' => false,
                    'constraints' => [
                        new IsTrue([
                            'message' => 'You must agree to the terms tha they have put'
                        ])
                    ]
                ]);


         function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => User::class,
            ]);
        }
    }
}
