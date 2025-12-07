<?php
// src/Service/MessagingService.php
namespace App\Service;

use App\Entity\Message;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class MessagingService
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Récupérer toutes les conversations d'un utilisateur
     *
     * @param User $user
     * @return array
     */
    public function getConversations(User $user): array
    {
        $qb = $this->em->getRepository(Message::class)
            ->createQueryBuilder('m')
            ->where('m.sender = :user OR m.receiver = :user')
            ->setParameter('user', $user)
            ->orderBy('m.createdAt', 'DESC');

        $messages = $qb->getQuery()->getResult();

        $conversations = [];
        foreach ($messages as $message) {
            $interlocutor = $message->getSender()->getId() === $user->getId()
                ? $message->getReceiver()
                : $message->getSender();

            // On garde uniquement le dernier message par interlocuteur
            if (!isset($conversations[$interlocutor->getId()])) {
                $conversations[$interlocutor->getId()] = [
                    'user' => $interlocutor,
                    'lastMessage' => $message,
                ];
            }
        }

        // Retourner sous forme d'array indexé
        return array_values($conversations);
    }

    /**
     * Récupérer tous les messages entre deux utilisateurs
     *
     * @param User $user1
     * @param User $user2
     * @return Message[]
     */
    public function getMessagesBetweenUsers(User $user1, User $user2): array
    {
        return $this->em->getRepository(Message::class)
            ->createQueryBuilder('m')
            ->where('(m.sender = :user1 AND m.receiver = :user2) OR (m.sender = :user2 AND m.receiver = :user1)')
            ->setParameter('user1', $user1)
            ->setParameter('user2', $user2)
            ->orderBy('m.createdAt', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Envoyer un message
     *
     * @param User $sender
     * @param User $receiver
     * @param string $content
     * @return Message
     */
    public function sendMessage(User $sender, User $receiver, string $content): Message
    {
        $message = new Message();
        $message->setSender($sender)
            ->setReceiver($receiver)
            ->setContent($content)
            ->setIsRead(false)
            ->setCreatedAt(new \DateTime());

        $this->em->persist($message);
        $this->em->flush();

        return $message;
    }

    /**
     * Marquer un message comme lu
     *
     * @param Message $message
     * @return void
     */
    public function markAsRead(Message $message): void
    {
        if (!$message->isRead()) {
            $message->setIsRead(true);
            $this->em->flush();
        }
    }
}
