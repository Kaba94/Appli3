<?php

namespace App\Controller;

use App\Repository\EventRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(EventRepository $repository, UserRepository $user)
    {
        return $this->render('home/index.html.twig', [
            'event_list' => $repository->findBy([], ['id' => 'DESC']),
        ]);
    }
}
