<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
class RegisterController extends AbstractController
{
    #[Route('/register', name: 'register')]
    public function register(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher
    ): Response
    {
        if ($request->isMethod('POST')) {
            // Récupération et nettoyage des champs
            $name = trim((string)$request->request->get('name'));
            $email = trim((string)$request->request->get('email'));
            $password = (string)$request->request->get('password');
            $confirmPassword = (string)$request->request->get('confirm_password');
            $phone = trim((string)$request->request->get('phone'));
            $bio = trim((string)$request->request->get('bio'));
            $adress = trim((string)$request->request->get('adress'));
            $status = (bool)$request->request->get('status');
            // Skills can come as comma-separated or as multiple inputs offeredSkill[]
            $offeredSkillRaw = trim((string)$request->request->get('offeredSkill', ''));
            $requestedSkillRaw = trim((string)$request->request->get('requestedSkill', ''));

            // Validation minimale
            if (empty($name) || empty($email) || empty($password)) {
                $this->addFlash('error', 'Veuillez remplir tous les champs obligatoires (*)');
                return $this->redirectToRoute('register');
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->addFlash('error', 'Adresse email invalide.');
                return $this->redirectToRoute('register');
            }

            if ($password !== $confirmPassword) {
                $this->addFlash('error', 'Les mots de passe ne correspondent pas.');
                return $this->redirectToRoute('register');
            }

            // Vérifier existence d'un utilisateur avec le même email
            $existingUser = $em->getRepository(User::class)->findOneBy(['email' => $email]);
            if ($existingUser) {
                $this->addFlash('error', 'Un compte existe déjà avec cet email.');
                return $this->redirectToRoute('register');
            }

            // Transformer les strings de skills en tableaux (trim + filter empty)
            $offeredSkill = [];
            if ($offeredSkillRaw !== '') {
                $offeredSkill = array_values(array_filter(array_map('trim', explode(',', $offeredSkillRaw))));
            }
            $requestedSkill = [];
            if ($requestedSkillRaw !== '') {
                $requestedSkill = array_values(array_filter(array_map('trim', explode(',', $requestedSkillRaw))));
            }

            // Création de l'entité User
            $user = new User();
            $user->setName($name);
            $user->setEmail($email);
            $user->setPhone($phone);
            $user->setBio($bio ?: null);
            $user->setAdress($adress ?: null);
            $user->setStatus($status);
            $user->setOfferedSkill($offeredSkill ?: null);
            $user->setRequestedSkill($requestedSkill ?: null);
            $user->setRoles(['ROLE_USER']);
            $user->setCreatedAt(new \DateTime()); // correspond au type \DateTime dans ton entité

            // Hash du mot de passe
            $hashed = $passwordHasher->hashPassword($user, $password);
            $user->setPassword($hashed);

            // Persist et flush
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Create Compte with success!');
            return $this->redirectToRoute('login');
        }




            return $this->render('security/register.html.twig');
    }
}
