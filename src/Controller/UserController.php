<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\ExchangeProposal;
class UserController extends AbstractController
{
    #[Route('/profile/dashboard', name: 'user_dashboard')]
    public function dashboard(EntityManagerInterface $em): Response
    {
        return $this->render('home/user_dashboard.html.twig');
    }
}

//
//namespace App\Controller;
//
//    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//    use Symfony\Component\HttpFoundation\Response;
//    use Symfony\Component\Routing\Annotation\Route;
//    use Doctrine\ORM\EntityManagerInterface;
//    use App\Entity\ExchangeProposal;
//    use App\Entity\Notification;
//
//class UserController extends AbstractController
//{
//    // src/Controller/UserController.php
//
//    #[Route('/profile/dashboard', name: 'user_dashboard')]
//    public function dashboard(EntityManagerInterface $em): Response
//    {
//        $user = $this->getUser();
//
//        // Proposals where user is requester
//        $madeProposals = $em->getRepository(ExchangeProposal::class)
//            ->createQueryBuilder('e')
//            ->where('e.requester = :user')
//            ->setParameter('user', $user)
//            ->getQuery()
//            ->getResult();
//
//        // Proposals where user owns the offered skill
//        $receivedProposals = $em->getRepository(ExchangeProposal::class)
//            ->createQueryBuilder('e')
//            ->join('e.offeredSkill', 's')
//            ->where('s.owner = :user')
//            ->setParameter('user', $user)
//            ->getQuery()
//            ->getResult();
//
//        // Notifications
//        $notifications = $em->getRepository(Notification::class)
//            ->findBy(['user' => $user, 'isRead' => false], ['createdAt' => 'DESC']);
//
//        return $this->render('exchange/listofproposals.html.twig', [
//            'madeProposals' => $madeProposals,
//            'receivedProposals' => $receivedProposals,
//            'notifications' => $notifications,
//        ]);
//    }
//
//}