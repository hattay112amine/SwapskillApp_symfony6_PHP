<?php

namespace App\Controller;

use App\Repository\EventRepository;
use App\Repository\SkillRepository;
use App\Repository\NotificationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/dashboard')]
class DashboardController extends AbstractController
{
    #[Route('/', name: 'app_dashboard')]
    public function index(
        EventRepository $eventRepository,
        SkillRepository $skillRepository,
        NotificationRepository $notificationRepository
    ): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login'); // Redirige si non connecté
        }

        // Événements organisés par l'utilisateur
        $organizedEvents = $eventRepository->findBy(['organizer' => $user]);

        // Événements où l'utilisateur est inscrit
        $registeredEvents = $eventRepository->findEventsUserRegistered($user);

        // Compétences de l'utilisateur
        $mySkills = $skillRepository->findBy(['owner' => $user]);

        // Compétences proposées et demandées
        $offeredSkills = $skillRepository->findBy(['owner' => $user, 'availability' => 'offered']);
        $requestedSkills = $skillRepository->findBy(['owner' => $user, 'availability' => 'requested']);

        // Notifications de l'utilisateur
        $notifications = $notificationRepository->findBy(
            ['user' => $user],
            ['createdAt' => 'DESC']
        );

        return $this->render('dashboard/index.html.twig', [
            'organizedEvents' => $organizedEvents,
            'registeredEvents' => $registeredEvents,
            'mySkills' => $mySkills,
            'offeredSkills' => $offeredSkills,
            'requestedSkills' => $requestedSkills,
            'notifications' => $notifications,
        ]);
    }
}
