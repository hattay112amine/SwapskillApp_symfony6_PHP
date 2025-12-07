<?php


namespace App\Controller;

use App\Entity\Notification;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;


use App\Entity\User;
use App\Service\NotificationService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

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
////////////////////////////////////////////////////
    public function __construct(
        private NotificationService $notificationService,
        private EntityManagerInterface $em
    ) {}

    #[Route('/notifications', name: 'notification_index')]
    public function index(): Response
    {
        $user = $this->getUser(); // utilisateur connecté
        $notifications = $this->notificationService->listNotifications($user);

        return $this->render('notification/index.html.twig', [
            'notifications' => $notifications
        ]);
    }

    #[Route('/mark-read/{id}', name: 'notification_mark_read', methods:['POST'])]
    public function markAsReadAjax(Notification $notification, EntityManagerInterface $em, Request $request): JsonResponse
    {
        if (!$this->isCsrfTokenValid('mark_read', $request->headers->get('X-CSRF-TOKEN'))) {
            return $this->json(['success' => false], 400);
        }

        $notification->setIsRead(true);
        $em->flush();

        return $this->json(['success' => true]);
    }


    #[Route('/notifications/send-rating', name: 'notification_send_rating', methods: ['POST'])]
    public function sendRating(Request $request): Response
    {
        $fromUser = $this->getUser();
        $toUserId = $request->request->get('toUserId');
        $score = $request->request->get('score');

        $toUser = $this->em->getRepository(User::class)->find($toUserId);
        if (!$toUser) {
            throw $this->createNotFoundException('Utilisateur introuvable.');
        }

        $content = sprintf("%s vous a donné un rate de %d", $fromUser->getName(), $score);
        $this->notificationService->createNotification($toUser, 'rating', $content);

        return $this->redirectToRoute('notification_index');
    }

    #[Route('/notifications/send-proposal', name: 'notification_send_proposal', methods: ['POST'])]
    public function sendProposal(Request $request): Response
    {
        $fromUser = $this->getUser();
        $toUserId = $request->request->get('toUserId');
        $offeredSkill = $request->request->get('offeredSkill');
        $requestedSkill = $request->request->get('requestedSkill');

        $toUser = $this->em->getRepository(User::class)->find($toUserId);
        if (!$toUser) {
            throw $this->createNotFoundException('Utilisateur introuvable.');
        }

        $content = sprintf(
            "%s vous a envoyé une proposition pour le skill %s (offre: %s)",
            $fromUser->getName(),
            $requestedSkill,
            $offeredSkill
        );

        $this->notificationService->createNotification($toUser, 'proposal', $content);

        return $this->redirectToRoute('notification_index');
    }

    #[Route('/notifications/send-acceptance', name: 'notification_send_acceptance', methods: ['POST'])]
    public function sendAcceptance(Request $request): Response
    {
        $fromUser = $this->getUser();
        $toUserId = $request->request->get('toUserId');

        $toUser = $this->em->getRepository(User::class)->find($toUserId);
        if (!$toUser) {
            throw $this->createNotFoundException('Utilisateur introuvable.');
        }

        $content = sprintf("%s a accepté votre proposition", $fromUser->getName());
        $this->notificationService->createNotification($toUser, 'acceptance', $content);

        return $this->redirectToRoute('notification_index');
    }





    
}
