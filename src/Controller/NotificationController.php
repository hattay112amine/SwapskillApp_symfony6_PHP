<?php

namespace App\Controller;

use App\Entity\Notification;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class NotificationController extends AbstractController
{
    #[Route('/notification/read/{id}', name: 'notification_read')]
    public function markAsRead(Notification $notification, EntityManagerInterface $em): RedirectResponse
    {
        $notification->setIsRead(true);
        $em->persist($notification);
        $em->flush();

        // Redirect to the proposal related to the notification if exists
        if ($notification->getProposal()) {
//            return $this->redirectToRoute('user_dashboard');
            return $this->redirectToRoute('listofproposals');
        }

        return $this->redirectToRoute('notifications_list');
    }

    #[Route('/notifications/read-all', name: 'notifications_read_all')]
    public function markAllAsRead(EntityManagerInterface $em): RedirectResponse
    {
        $user = $this->getUser();
        $notifications = $em->getRepository(Notification::class)->findBy(['user' => $user]);

        foreach ($notifications as $note) {
            $note->setIsRead(true);
            $em->persist($note);
        }

        $em->flush();
        return $this->redirectToRoute('notifications_list');
    }
    #[Route('/notifications', name: 'notifications_list')]
    public function listNotifications(EntityManagerInterface $em)
    {
        $user = $this->getUser();
        $notifications = $em->getRepository(Notification::class)->findBy(['user' => $user], ['createdAt' => 'DESC']);

        return $this->render('notifications/list.html.twig', [
            'notifications' => $notifications,
        ]);
    }
}
