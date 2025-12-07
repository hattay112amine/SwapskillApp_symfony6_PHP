<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Event;
use App\Entity\EventRegistration;
use App\Entity\Notification;
use Doctrine\ORM\EntityManagerInterface;

final class EventRegistrationController extends AbstractController
{

    #[Route('/event/{id}/register', name: 'app_event_register', methods: ['POST'])]
    public function register(Event $event, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        if (!$user) {
            $this->addFlash('error', 'Vous devez être connecté pour vous inscrire.');
            return $this->redirectToRoute('app_event_index');
        }

        // Vérifier si déjà inscrit
        $existing = $em->getRepository(EventRegistration::class)->findOneBy([
            'event' => $event,
            'participant' => $user
        ]);

        if ($existing) {
            $this->addFlash('warning', 'Vous êtes déjà inscrit à cet événement.');
            return $this->redirectToRoute('app_event_show', ['id' => $event->getId()]);
        }
        // Vérifier la capacité
        if ($event->getCapacity() <= 0) {
            $this->addFlash('error', 'Cet événement est complet. Impossible de s\'inscrire.');
            return $this->redirectToRoute('app_event_show', ['id' => $event->getId()]);
        }

        // Créer l'inscription
        $registration = new EventRegistration();
        $registration->setEvent($event);
        $registration->setParticipant($user);
        $registration->setRegisteredAt(new \DateTime());
        $registration->setStatus('pending');

        $em->persist($registration);
        // Diminuer la capacité de l'événement
        $event->setCapacity($event->getCapacity() - 1);
        $em->persist($event);
        $em->flush();

        $this->addFlash('success', 'Inscription réussie !');

        // Notification au organisateur
        $notification = new Notification();
        $notification->setUser($event->getOrganizer());
        $notification->setType('registration');
        $notification->setContent(
            $user->getName() . ' s\'est inscrit à votre événement "' . $event->getTitle() . '"'
        );
        $notification->setCreatedAt(new \DateTime());

        $em->persist($notification);
        $em->flush();

        return $this->redirectToRoute('app_event_show', ['id' => $event->getId()]);
    }
}
