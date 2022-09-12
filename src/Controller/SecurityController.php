<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        //The credentials of one user
        /*$user = new User();
        $user->setEmail('ameta@gmail.com');
        $user->setFirstName('Anisa');
        $user->setLastName('Meta');
        $user->setRoles(['ROLE_USER']);
        $user->setPlainPassword('epoka123');
        $password = $passwordHasher->hashPassword($user, $user->getPlainPassword());
        $user->setPassword($password);
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }
*/
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        /*$entityManager->persist($user);
        $entityManager->flush();*/

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout",name="app_logout")
     */
    public function logout()
    {
        throw new \Exception('logout() should never be reached.');
    }
}
