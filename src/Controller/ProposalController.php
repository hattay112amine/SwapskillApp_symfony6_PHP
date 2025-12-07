<?php

// src/Controller/ProposalController.php
namespace App\Controller;

use App\Entity\ExchangeProposal;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Notification;
use Symfony\Component\HttpFoundation\Response;



class ProposalController extends AbstractController
{
    #[Route('/debug/user', name: 'debug_user')]
    public function debugUser(): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return new Response('No user logged in');
        }

        $data = [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'name' => $user->getName(),
            'roles' => $user->getRoles(),
        ];

        return new Response(json_encode($data, JSON_PRETTY_PRINT), 200, [
            'Content-Type' => 'application/json'
        ]);
    }


    #[Route('/proposal/accept/{id}', name: 'proposal_accept')]
    public function acceptProposal(ExchangeProposal $proposal, EntityManagerInterface $em)
    {
        $proposal->setStatus('accepted');
        $em->persist($proposal);

        // Notify the requester
        $notification = new Notification();
        $notification->setUser($proposal->getRequester()); // the user who made the original request
        $notification->setProposal($proposal);
        $notification->setType('accepted');
        $notification->setIsRead(false);
        $notification->setCreatedAt(new \DateTime());
        $notification->setContent('Your proposal has been accepted!');

        $em->persist($notification);
        $em->flush();

        $this->addFlash('success', 'Proposal accepted successfully!');
        return $this->redirectToRoute('listofproposals');
    }

    #[Route('/proposal/refuse/{id}', name: 'proposal_refuse')]
    public function refuseProposal(ExchangeProposal $proposal, EntityManagerInterface $em)
    {
        $proposal->setStatus('refused');
        $em->persist($proposal);

        // Notify the requester
        $notification = new Notification();
        $notification->setUser($proposal->getRequester());
        $notification->setProposal($proposal);
        $notification->setType('refused');
        $notification->setIsRead(false);
        $notification->setCreatedAt(new \DateTime());
        $notification->setContent('Your proposal has been refused!');

        $em->persist($notification);
        $em->flush();

        $this->addFlash('danger', 'Proposal refused.');
        return $this->redirectToRoute('listofproposals');
    }
    #[Route('/proposal/complete/{id}', name: 'proposal_complete')]
    public function completeProposal(ExchangeProposal $proposal, EntityManagerInterface $em): RedirectResponse
    {
        // Add authorization check: only requester or receiver can mark as complete
        $currentUser = $this->getUser();
        if ($proposal->getRequester() !== $currentUser && $proposal->getReceiver() !== $currentUser) {
            throw $this->createAccessDeniedException('You cannot complete this exchange.');
        }

        $proposal->setStatus('completed'); // *** New Status ***
        $em->persist($proposal);
        $em->flush();

        $this->addFlash('success', 'Exchange marked as completed! Please rate your partner below.');
        return $this->redirectToRoute('listofproposals');
    }

}
