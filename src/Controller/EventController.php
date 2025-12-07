<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

#[Route('/event')]
final class EventController extends AbstractController
{
    #[Route(name: 'app_event_index', methods: ['GET'])]
    public function index(EventRepository $eventRepository): Response
    {
        return $this->render('event/index.html.twig', [
            'events' => $eventRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_event_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $event = new Event();
        // Formulaire créé directement ici
        $form = $this->createFormBuilder($event)
            ->add('title', TextType::class)
            ->add('description', TextType::class)
            ->add('type', TextType::class)
            ->add('date', DateTimeType::class, ['widget' => 'single_text'])
            ->add('location', TextType::class)
            ->add('price', MoneyType::class, ['currency' => 'TND'])
            ->add('capacity', IntegerType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // 1. Pré-remplir l’organisateur depuis l’utilisateur connecté
            $organizer = $this->getUser();
            if (!$organizer) {
                throw $this->createAccessDeniedException('Vous devez être connecté pour créer un événement.');
            }
            // 2. Pré-remplir la date de création
            $event->setOrganizer($organizer);

            $event->setCreatedAt(new \DateTime());

            $entityManager->persist($event);
            $entityManager->flush();

            $this->addFlash('success', 'Événement créé avec succès !');

            return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('event/new.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_event_show', methods: ['GET'])]
    public function show(Event $event): Response
    {
        return $this->render('event/show.html.twig', [
            'event' => $event,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_event_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Event $event, EntityManagerInterface $entityManager): Response
    {
        // Formulaire directement ici
        $form = $this->createFormBuilder($event)
            ->add('title', TextType::class)
            ->add('description', TextType::class)
            ->add('type', TextType::class)
            ->add('date', DateTimeType::class, ['widget' => 'single_text'])
            ->add('location', TextType::class)
            ->add('price', MoneyType::class, ['currency' => 'TND'])
            ->add('capacity', IntegerType::class)
            ->getForm();


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Événement modifié avec succès !');

            return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('event/edit.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_event_delete', methods: ['POST'])]
    public function delete(Request $request, Event $event, EntityManagerInterface $entityManager): Response
    {
        // Vérifie le token CSRF
        if ($this->isCsrfTokenValid('delete'.$event->getId(), $request->request->get('_token'))) {
            $entityManager->remove($event);
            $entityManager->flush();
            $this->addFlash('success', 'Événement supprimé avec succès !');
        } else {
            $this->addFlash('danger', 'Token CSRF invalide. Suppression annulée.');
        }

        return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
    }

}
