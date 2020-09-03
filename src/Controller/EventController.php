<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EditFormType;
use App\Form\EventFormType;
use App\Repository\EventRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class EventController extends AbstractController
{

    /**
     * Création de nouveaux evenements
     * @Route("/create-event", name="app_create-event")
     * IsGranted("ROLE_USER")
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

    /**
     * Page d'un événement
     * @Route("/event/{id}", name="event_page")
     * 
     */
    public function eventPage(Event $event)
    {
        return $this->render('event/event_page.html.twig', [
            'event' => $event
        ]);
    }

    /**
     * Liste des événements par utilisateur
     * @Route("/my_event", name="my_event")
     */
    public function eventList(EventRepository $repository, UserRepository $user)
    {
        $events = $repository->findBy(['auteur' => $this->getUser()]);
        return $this->render('event/my_event.html.twig', [
            
             'events' => $events
        ]);
    }

    /**
     * Modifier un événement
     * @Route("/event_edit/{id}", name="event_edit")
     * @IsGranted("EVENT_EDIT", subject="event")
     */
    public function eventEdit(EventRepository $repository, Request $request, EntityManagerInterface $manager, Event $event)
    {
        $form = $this->createForm(EditFormType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupération des données de formulaire
            $event = $form->getData();
            $event->setAuteur($this->getUser()); 


            $manager->flush();

            return $this->redirectToRoute('my_event');
        }

        return $this->render('event/event_edit.html.twig', [
            'editForm' => $form->createView(),
        ]);
    }

    /**
     * Suppression d'un événement
     * @Route ("/event_remove/{id}", name="event_remove")
     * @IsGranted("EVENT_EDIT", subject="event")
     */
    public function supprimer(Event $event, EntityManagerInterface $manager, Request $request)
    {
        // Récuparation du jeton csrf
        $token = $request->query->get('token');

        // Vérification de la validité de la clé 
        if ($this->isCsrfTokenValid('event_remove', $token))
        {
            // Suppression
            $manager->remove($event);
            $manager->flush();
        }
        // redirection
        return $this->redirectToRoute('my_event');
    }

}