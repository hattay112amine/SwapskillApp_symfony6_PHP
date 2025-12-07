<?php
// src/Service/NotificationService.php
namespace App\Service;

use App\Entity\User;
use App\Entity\Notification;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class NotificationService
{
    private EntityManagerInterface $em;
    private MailerInterface $mailer;

    public function __construct(EntityManagerInterface $em, MailerInterface $mailer)
    {
        $this->em = $em;
        $this->mailer = $mailer;
    }


    /**
     * Crée une notification pour un utilisateur et envoie un email
     */
    public function createNotification(User $user, string $type, string $content): Notification
    {
        // Création de la notification
        $notification = new Notification();
        $notification->setType($type)
            ->setContent($content);

        $this->em->persist($notification);
        $this->em->flush();

        // Envoi de l'email
        $this->sendEmail($user, $type, $content);

        return $notification;
    }

    /**
     * Envoi un email à l'utilisateur
     */
    private function sendEmail(User $user, string $type, string $content): void
    {
        $email = (new Email())
            ->from('no-reply@swapskillapp.com')
            ->to($user->getEmail())
            ->subject($this->getSubject($type))
            ->text($content);

        $this->mailer->send($email);
    }

    /**
     * Retourne le sujet de l'email selon le type de notification
     */
    private function getSubject(string $type): string
    {
        return match($type) {
            'rating' => 'Vous avez reçu un nouveau rating',
            'proposal' => 'Nouvelle proposition reçue',
            'acceptance' => 'Une proposition a été acceptée',
            default => 'Nouvelle notification',
        };
    }

    /**
     * Récupère toutes les notifications triées par date décroissante
     */
    public function listNotifications(User $user): array
    {
        return $this->em->getRepository(Notification::class)
            ->findBy([], ['createdAt' => 'DESC']);
        // Optionnel : filtrer par utilisateur si tu l'ajoutes dans Notification
    }

    /**
     * Marque une notification comme lue
     */
    public function markAsRead(): self
    {
        $this->isRead = true;
        return $this;
    }
}
