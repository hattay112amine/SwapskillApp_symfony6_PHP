<?php


namespace App\Controller;

use App\Entity\ExchangeProposal;
use App\Entity\Notification;
use App\Form\ExchangeProposalType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExchangeController extends AbstractController
{
    #[Route('/exchange/listofproposals', name: 'listofproposals')]
    public function dashboard(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        // Proposals where user is requester
        $madeProposals = $em->getRepository(ExchangeProposal::class)
            ->createQueryBuilder('e')
            ->where('e.requester = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();

        // Proposals where user owns the offered skill
        $receivedProposals = $em->getRepository(ExchangeProposal::class)
            ->createQueryBuilder('e')
            ->join('e.offeredSkill', 's')
            ->where('s.owner = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();

        // Notifications
        $notifications = $em->getRepository(Notification::class)
            ->findBy(['user' => $user, 'isRead' => false], ['createdAt' => 'DESC']);

        return $this->render('exchange/listofproposals.html.twig', [
            'madeProposals' => $madeProposals,
            'receivedProposals' => $receivedProposals,
            'notifications' => $notifications,
        ]);
    }
    #[Route('/exchange/propose', name: 'exchange_propose')]
    public function propose(Request $request, EntityManagerInterface $em): Response
    {
        // Only logged-in users can propose
        $this->denyAccessUnlessGranted('ROLE_USER');

        $proposal = new ExchangeProposal();
        $form = $this->createForm(ExchangeProposalType::class, $proposal);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Set additional fields automatically
            $proposal->setRequester($this->getUser());

            $proposal->setReceiver($proposal->getRequestedSkill()->getOwner());

            $proposal->setStatus('pending');
            $proposal->setCreatedAt(new \DateTime());

            // Save to database
            $em->persist($proposal);
            $em->flush();

            // Flash message
            $this->addFlash('success', 'Your exchange proposal has been submitted.');

            // Redirect (user dashboard or homepage)
//            return $this->redirectToRoute('user_dashboard');
            return $this->redirectToRoute('listofproposals');
        }

        return $this->render('exchange/propose.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
