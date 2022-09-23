<?php

namespace App\Controller;

use App\Entity\Building;
use App\Entity\Room;
use App\Entity\User;
use App\Form\UserRegistrationFormType;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Profiler\Profiler;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\User\UserInterface;
use function Zenstruck\Foundry\faker;


class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, UserPasswordHasherInterface $passwordHasher,
                          EntityManagerInterface $entityManager): Response
    {

        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();


        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }


    #[Route('/logout', name: 'app_logout', methods: ['GET'])]
    public function logout()
    {
        throw new \Exception('logout() should never be reached.');
    }


     #[Route("/register", "app_register", methods: ['GET', 'POST'])]

    public function register(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher,
                             LoginFormAuthenticator $formAuthenticator,Security $security){

        $form = $this->createForm( UserRegistrationFormType::class)->remove('Roles');
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $form->getData();



            $user->setPassword($passwordHasher->hashPassword($user,$form['plainPassword']->getData()));

            if (true === $form['agreeTerms']->getData()) {
                $user->agreeTerms();
            }



            $em->persist($user);
            $em->flush();
      }

        return $this->render('security/register.html.twig', [
          'registrationForm' => $form->createView(),
        ]);

    }

}
