<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\ExchangeProposal;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;

class ChatController extends AbstractController
{
    #[Route('/chat/{proposalId}', name: 'chat_session', methods: ['GET', 'POST'])]
    public function chatSession(
        #[MapEntity(id: 'proposalId')] ExchangeProposal $proposal,
        Request $request,
        EntityManagerInterface $em
    ): Response
    {
        $user = $this->getUser();

        $userId = $user->getId();

        // Check if user is authorized to access this chat (compare IDs)
        if ($userId !== $proposal->getRequester()->getId() &&
            $userId !== $proposal->getReceiver()->getId()) {
            throw $this->createAccessDeniedException('You are not authorized to access this chat.');
        }

        if ($request->isMethod('POST')) {
            $content = $request->request->get('message');

            if ($content) {
                $message = new Message();
                $message->setSender($user);

                // Set receiver: the other user in the proposal (compare IDs)
                $receiver = $proposal->getRequester()->getId() === $userId
                    ? $proposal->getReceiver()
                    : $proposal->getRequester();
                $message->setReceiver($receiver);

                $message->setExchangeProposal($proposal);
                $message->setContent(trim($content));
                $message->setIsRead(false);
                $message->setCreatedAt(new \DateTime());

                $em->persist($message);
                $em->flush();

                return $this->redirectToRoute('chat_session', ['proposalId' => $proposal->getId()]);
            }
        }

        // Fetch all messages for this proposal
        $messages = $em->getRepository(Message::class)->findBy(
            ['exchangeProposal' => $proposal],
            ['createdAt' => 'ASC']
        );

        // Get the chat partner (compare IDs)
        $user = $this->getUser();

        $partner = ($proposal->getRequester()->getId() == $user->getId())
            ? $proposal->getReceiver()
            : $proposal->getRequester();

        return $this->render('chat/session.html.twig', [
            'proposal' => $proposal,
            'user' => $user,
            'partner' => $partner,
            'messages' => $messages,
        ]);
    }
}