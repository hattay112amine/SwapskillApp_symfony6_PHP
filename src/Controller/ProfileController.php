<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Entity\User;
use App\Form\UserType;

final class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    // #[IsGranted('ROLE_USER')] // commenter pour tester
    public function index(): \Symfony\Component\HttpFoundation\Response
    {
        // Simuler un utilisateur complet
        $user = new User();
        $user->setName('Amine Test')
            ->setEmail('amine@test.com')
            ->setPhone('12345678')
            ->setBio('Bio de test')
            ->setAdress('Adresse test')
            ->setRoles(['ROLE_USER'])
            ->setStatus(true)
            ->setOfferedSkill(['PHP', 'Symfony'])
            ->setRequestedSkill(['JavaScript'])
            ->setCreatedAt(new \DateTime());

        // Pour récupérer l'utilisateur réel depuis la DB, enlever le commentaire :
        // $user = $this->getUser();

        return $this->render('profile/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/profile/edit', name: 'app_profile_edit')]
    // #[IsGranted('ROLE_USER')] // commenter pour tester
    public function edit(Request $request, EntityManagerInterface $em, SluggerInterface $slugger)
    {
        // Simuler un utilisateur complet
        $user = new User();
        $user->setName('Amine Test')
            ->setEmail('amine@test.com')
            ->setPhone('12345678')
            ->setBio('Bio de test')
            ->setAdress('Adresse test')
            ->setRoles(['ROLE_USER'])
            ->setStatus(true)
            ->setOfferedSkill(['PHP', 'Symfony'])
            ->setRequestedSkill(['JavaScript'])
            ->setCreatedAt(new \DateTime());

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // ======================
            // Gestion de l'upload de photo (commentée pour l'instant)
            // ======================
            /*
            $photoFile = $form->get('photo')->getData();
            if ($photoFile) {
                $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$photoFile->guessExtension();

                try {
                    $photoFile->move(
                        $this->getParameter('photos_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('danger', 'Erreur lors de l\'upload de la photo.');
                }

                $user->setPhoto($newFilename);
            }
            */

            $em->flush();
            $this->addFlash('success', 'Profil mis à jour avec succès');
            return $this->redirectToRoute('app_profile');
        }

        return $this->render('profile/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
