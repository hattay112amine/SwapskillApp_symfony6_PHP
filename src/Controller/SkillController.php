<?php

namespace App\Controller;

use App\Entity\Skill;
use App\Form\SkillType;
use App\Repository\SkillRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/skill')]
final class SkillController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em) {}

    // Vérifie que la compétence appartient à l'utilisateur connecté
    private function checkOwner(Skill $skill)
    {
        if ($skill->getOwner() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous ne pouvez pas accéder à cette compétence.');
        }
    }

    #[Route(name: 'app_skill_index', methods: ['GET'])]
    public function index(SkillRepository $skillRepository): Response
    {
        $user = $this->getUser();
        $skills = $skillRepository->findBy(['owner' => $user]);

        return $this->render('skill/index.html.twig', [
            'skills' => $skills,
        ]);
    }

    #[Route('/new', name: 'app_skill_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {

            $skill = new Skill();
            $skill->setTitle($request->request->get('title'));
            $skill->setDescription($request->request->get('description'));
            $skill->setAvailability($request->request->get('availability'));
            $skill->setLevel($request->request->get('level'));

            $category = $request->request->get('category');
            if ($category === 'other') {
                $category = $request->request->get('category_other');
            }
            $skill->setCategory($category);

            $user = $this->getUser();
            if (!$user) {
                throw $this->createAccessDeniedException('You must be logged in to add a skill.');
            }

            $skill->setOwner($user);

            $entityManager->persist($skill);
            $entityManager->flush();

            return $this->redirectToRoute('app_skill_index');
        }

        return $this->render('skill/new.html.twig');
    }

    #[Route('/{id}', name: 'app_skill_show', methods: ['GET'])]
    public function show(Skill $skill): Response
    {
        $this->checkOwner($skill);

        return $this->render('skill/show.html.twig', [
            'skill' => $skill,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_skill_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Skill $skill, EntityManagerInterface $entityManager): Response
    {
        $this->checkOwner($skill);


        $form = $this->createForm(SkillType::class, $skill);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_skill_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('skill/edit.html.twig', [
            'skill' => $skill,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_skill_delete', methods: ['POST'])]
    public function delete(Request $request, Skill $skill, EntityManagerInterface $entityManager): Response
    {
        $this->checkOwner($skill);

        if ($this->isCsrfTokenValid('delete'.$skill->getId(), $request->request->get('_token'))) {
            $entityManager->remove($skill);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_skill_index', [], Response::HTTP_SEE_OTHER);
    }

    // Page pour explorer les compétences des autres utilisateurs

    #[Route('/browse', name: 'browse_skills')]
    public function browseSkills(Request $request): Response
    {
        $currentUser = $this->getUser();

        $query = $request->query->get('q', '');
        $filterType = $request->query->get('type', 'all');

        // On récupère toutes les compétences sauf celles du user connecté
        $qb = $this->em->getRepository(Skill::class)
            ->createQueryBuilder('s')
            ->where('s.owner != :currentUser')
            ->setParameter('currentUser', $currentUser);

        // Recherche textuelle sur le nom de la compétence
        if (!empty($query)) {
            $qb->andWhere('LOWER(s.name) LIKE :search')
                ->setParameter('search', '%' . strtolower($query) . '%');
        }

        // Filtrage par type si renseigné
        if ($filterType !== 'all') {
            $qb->andWhere('s.type = :type')
                ->setParameter('type', $filterType);
        }

        $skills = $qb->getQuery()->getResult();

        return $this->render('skill/browse.html.twig', [
            'skills' => $skills,
            'query' => $query,
            'filterType' => $filterType,
        ]);
    }

}
