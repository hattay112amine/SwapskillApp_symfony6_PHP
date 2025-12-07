<?php

// src/EventSubscriber/TwigGlobalsSubscriber.php
namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\Security\Core\Security;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Environment;
use App\Entity\Notification;

class TwigGlobalsSubscriber implements EventSubscriberInterface
{
    private $twig;
    private $security;
    private $em;

    public function __construct(Environment $twig, Security $security, EntityManagerInterface $em)
    {
        $this->twig = $twig;
        $this->security = $security;
        $this->em = $em;
    }

    public function onKernelController(ControllerEvent $event)
    {
        $user = $this->security->getUser();
        $notifications = [];

        if ($user) {
            $notifications = $this->em->getRepository(Notification::class)
                ->findBy(['user' => $user, 'isRead' => false], ['createdAt' => 'DESC']);
        }

        $this->twig->addGlobal('notifications', $notifications);
    }

    public static function getSubscribedEvents()
    {
        return [
            ControllerEvent::class => 'onKernelController',
        ];
    }
}
