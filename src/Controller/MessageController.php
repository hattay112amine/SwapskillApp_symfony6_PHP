<?php
// src/Controller/MessageController.php
namespace App\Controller;

use App\Entity\Message;
use App\Entity\User;
use App\Service\MessagingService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/messages')]
class MessageController extends AbstractController
{
    private MessagingService $messagingService;
    private EntityManagerInterface $em;

    public function __construct(MessagingService $messagingService, EntityManagerInterface $em)
    {
        $this->messagingService = $messagingService;
        $this->em = $em;
    }

    #[Route('/', name: 'app_messages_index', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        $user = $this->getUser();

        // Récupérer tous les autres utilisateurs pour démarrer une conversation
        $users = $this->em->getRepository(User::class)
            ->createQueryBuilder('u')
            ->where('u != :currentUser')
            ->setParameter('currentUser', $user)
            ->getQuery()
            ->getResult();

        // Récupérer la liste des conversations existantes
        $conversations = $this->messagingService->getConversations($user);

        // Déterminer le destinataire sélectionné
        $receiver = null;
        if ($request->query->get('receiver_id')) {
            $receiver = $this->em->getRepository(User::class)
                ->find($request->query->get('receiver_id'));
        } elseif (count($conversations) > 0) {
            $receiver = $conversations[0]['user'];
        }

        // Récupérer les messages pour la conversation sélectionnée
        $messages = [];
        if ($receiver) {
            $messages = $this->messagingService->getMessagesBetweenUsers($user, $receiver);
        }

        // Gestion du formulaire d'envoi de message
        if ($request->isMethod('POST')) {
            $content = $request->request->get('content');
            if ($receiver && $content) {
                $this->messagingService->sendMessage($user, $receiver, $content);
                return $this->redirectToRoute('app_messages_index', ['receiver_id' => $receiver->getId()]);
            } else {
                $this->addFlash('error', 'Le message ne peut pas être vide.');
                return $this->redirectToRoute('app_messages_index', ['receiver_id' => $receiver ? $receiver->getId() : null]);
            }
        }

        return $this->render('message/index.html.twig', [
            'users' => $users,
            'conversations' => $conversations,
            'receiver' => $receiver,
            'messages' => $messages,
        ]);
    }

    #[Route('/conversation/{userId}/messages', name: 'app_messages_conversation', methods: ['GET'])]
    public function getConversationMessages(User $otherUser): Response
    {
        $user = $this->getUser();
        $messages = $this->messagingService->getMessagesBetweenUsers($user, $otherUser);

        $data = [];
        foreach ($messages as $message) {
            $data[] = [
                'id' => $message->getId(),
                'content' => $message->getContent(),
                'created_at' => $message->getCreatedAt()?->format('d/m/Y H:i'),
                'sender' => [
                    'id' => $message->getSender()?->getId(),
                    'name' => $message->getSender()?->getName(),
                ],
                'receiver' => [
                    'id' => $message->getReciever()?->getId(),
                    'name' => $message->getReciever()?->getName(),
                ],
                'is_read' => $message->isRead(),
            ];
        }

        return $this->json($data);
    }

    #[Route('/{id}/read', name: 'message_mark_read', methods: ['POST'])]
    public function markAsRead(Message $message): Response
    {
        $this->messagingService->markAsRead($message);
        $this->addFlash('success', 'Message marqué comme lu.');
        return $this->redirectToRoute('app_messages_index');
    }
    #[Route('/messages/{receiverId}/fetch', name: 'app_messages_fetch', methods: ['GET'])]
    public function fetchMessages(User $receiver, MessageRepository $messageRepository): JsonResponse
    {
        $user = $this->getUser();
        $messages = $messageRepository->findConversationMessages($user, $receiver);

        $data = [];

        foreach ($messages as $msg) {
            $data[] = [
                'id' => $msg->getId(),
                'sender' => $msg->getSender()->getName(),
                'content' => $msg->getContent(),
                'isRead' => $msg->isRead(),
                'createdAt' => $msg->getCreatedAt()->format('Y-m-d H:i:s'),
            ];
        }

        return $this->json($data);
    }
}
