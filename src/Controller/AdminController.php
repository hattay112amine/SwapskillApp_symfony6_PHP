<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Skill;
use App\Entity\Rating;
use App\Entity\Event;
use App\Repository\UserRepository;
use App\Repository\SkillRepository;
use App\Repository\RatingRepository;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AdminController extends AbstractController
{
    #[Route('/admin/dashboard', name: 'admin_dashboard')]
    public function dashboard(
        UserRepository $userRepository,
        SkillRepository $skillRepository,
        RatingRepository $ratingRepository,
        EventRepository $eventRepository
    ): Response {
        // Vérifie que l'utilisateur a le rôle ROLE_ADMIN
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $users = $userRepository->findAll();
        $skills = $skillRepository->findAll();
        $ratings = $ratingRepository->findAll();
        $events = $eventRepository->findAll();

        return $this->render('home/admin_dashboard.html.twig', [
            'users' => $users,
            'skills' => $skills,
            'ratings' => $ratings,
            'events' => $events,
        ]);
    }

    // --- Utilisateurs ---
    #[Route('/admin/users/block/{id}', name: 'admin_user_block')]
    public function blockUser(User $user, EntityManagerInterface $em): RedirectResponse
    {
        $user->setStatus(!$user->isStatus()); // toggle block/unblock
        $em->flush();
        $this->addFlash('success', 'Statut utilisateur mis à jour avec succès.');
        return $this->redirectToRoute('admin_dashboard');
    }

    #[Route('/admin/users/delete/{id}', name: 'admin_user_delete')]
    public function deleteUser(User $user, EntityManagerInterface $em): RedirectResponse
    {
        $em->remove($user);
        $em->flush();
        $this->addFlash('success', 'Utilisateur supprimé avec succès.');
        return $this->redirectToRoute('admin_dashboard');
    }

    // --- Compétences ---
    #[Route('/admin/skills/delete/{id}', name: 'admin_skill_delete')]
    public function deleteSkill(Skill $skill, EntityManagerInterface $em): RedirectResponse
    {
        $em->remove($skill);
        $em->flush();
        $this->addFlash('success', 'Compétence supprimée avec succès.');
        return $this->redirectToRoute('admin_dashboard');
    }

    // --- Notes/Commentaires ---
    #[Route('/admin/ratings/delete/{id}', name: 'admin_rating_delete')]
    public function deleteRating(Rating $rating, EntityManagerInterface $em): RedirectResponse
    {
        $em->remove($rating);
        $em->flush();
        $this->addFlash('success', 'Commentaire/Note supprimé(e) avec succès.');
        return $this->redirectToRoute('admin_dashboard');
    }

    // --- Événements ---
    #[Route('/admin/events/approve/{id}', name: 'admin_event_approve')]
    public function approveEvent(Event $event, EntityManagerInterface $em): RedirectResponse
    {
        // Ici tu peux ajouter un champ "status" si nécessaire
        $this->addFlash('success', 'Événement approuvé avec succès.');
        return $this->redirectToRoute('admin_dashboard');
    }

    #[Route('/admin/events/delete/{id}', name: 'admin_event_delete')]
    public function deleteEvent(Event $event, EntityManagerInterface $em): RedirectResponse
    {
        $em->remove($event);
        $em->flush();
        $this->addFlash('success', 'Événement supprimé avec succès.');
        return $this->redirectToRoute('admin_dashboard');
    }
}
