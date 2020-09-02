<?php

namespace App\Controller;

use App\Form\EventFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class EventController extends AbstractController
{

    /**
     * @Route("/create-event", name="app_create-event")
     */
    public function createEvent(EntityManagerInterface $manager, UserRepository $repository, Request $request): Response
    {
        $form = $this->createForm(EventFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupération des données de formulaire
            $event = $form->getData();
            $event->setAuteur($this->getUser()); 


            $manager->persist($event);
            $manager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('event/create-event.html.twig', [
            'eventForm' => $form->createView(),
        ]);
    }
}
