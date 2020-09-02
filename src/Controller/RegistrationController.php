<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\PasswordFormType;
use App\Form\ProfileFormType;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Service\EmailSender;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    private $manager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->manager = $entityManager;
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $form = $this->createForm(RegistrationFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupération des données de formulaire (entité User + mot de passe)
            $user = $form->getData();
            $password = $form->get('plainPassword')->getData();

            // hash du mot de passe
            $user
                ->setPassword($passwordEncoder->encodePassword($user, $password))
            ;

            $this->manager->persist($user);
            $this->manager->flush();
            return $this->redirectToRoute('home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * Modifier les information des utilisateurs
     * @Route("/profile", name="app_profile")
     */
    public function modifUsername(UserRepository $repository, EntityManagerInterface $entityManager,  UserPasswordEncoderInterface $passwordEncoder, Request $request)
    {
        $passwordForm = $this->createForm(PasswordFormType::class);
        $passwordForm->handleRequest($request);

        if ($passwordForm->isSubmitted() && $passwordForm->isValid()) {
            // Récupération des données de formulaire (entité User + mot de passe)
            $user = $passwordForm->getData();
            $password = $passwordForm->get('plainPassword')->getData();
            $this->manager->flush();

            $this->addFlash('success', 'Vos information on bien été modifiée !');
            return $this->redirectToRoute('home');
        }

        $profileForm = $this->createForm(ProfileFormType::class, $this->getUser());
        $profileForm->handleRequest($request);

        if ($profileForm->isSubmitted() && $profileForm->isValid()) {
            // Récupération des données de formulaire (entité User + mot de passe)
            $user = $profileForm->getData();
            $this->manager->flush();

            $this->addFlash('success', 'Vos information on bien été modifiée !');
            return $this->redirectToRoute('home');
        }

        return $this->render('profile/profile.html.twig', [
            'profileForm' => $profileForm->createView(),
            'passwordForm' => $passwordForm->createView(),
        ]);
    }
}
