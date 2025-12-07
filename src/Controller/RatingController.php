<?php
// src/Controller/RatingController.php (New file or add to an existing controller)

namespace App\Controller;

use App\Entity\ExchangeProposal;
use App\Entity\Rating;
use App\Form\RatingType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RatingController extends AbstractController
{
    #[Route('/exchange/{id}/rate', name: 'exchange_rate')]
    public function rate(ExchangeProposal $proposal, Request $request, EntityManagerInterface $em): Response
    {
        $currentUser = $this->getUser();

        // 1. Authorization check
        if ($proposal->getRequester() !== $currentUser && $proposal->getReceiver() !== $currentUser) {
            throw $this->createAccessDeniedException('You are not a participant in this exchange.');
        }

        // 2. Identify the partner to be rated
        $partner = ($proposal->getRequester() === $currentUser)
            ? $proposal->getReceiver()
            : $proposal->getRequester();

        // 3. Prevent duplicate rating (optional, but good practice)
        $existingRating = $em->getRepository(Rating::class)->findOneBy([
            'exchangeProposal' => $proposal,
            'fromUser' => $currentUser,
        ]);

        if ($existingRating) {
            $this->addFlash('warning', 'You have already rated your partner for this exchange.');
            return $this->redirectToRoute('listofproposals');
        }

        $rating = new Rating();
        $form = $this->createForm(RatingType::class, $rating);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Set required relations/data
            $rating->setFromUser($currentUser);
            $rating->setToUser($partner);
            $rating->setExchangeProposal($proposal);
            $rating->setCreatedAt(new \DateTime());

            $em->persist($rating);
            $em->flush();

            $this->addFlash('success', 'Thank you! Your rating has been submitted for ' . $partner->getName() . '.');
            return $this->redirectToRoute('listofproposals');
        }

        return $this->render('rating/rate.html.twig', [
            'form' => $form->createView(),
            'proposal' => $proposal,
            'partner' => $partner,
        ]);
    }
}