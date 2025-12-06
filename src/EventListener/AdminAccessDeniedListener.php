<?php
// src/EventListener/AdminAccessDeniedListener.php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedHttpException;

class AdminAccessDeniedListener
{
    private RequestStack $requestStack;
    private RouterInterface $router;

    public function __construct(RequestStack $requestStack, RouterInterface $router)
    {
        $this->requestStack = $requestStack;
        $this->router = $router;
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        // On intercepte AccessDeniedHttpException
        if ($exception instanceof AccessDeniedHttpException) {
            $session = $this->requestStack->getSession();
            if ($session) {
                $session->getFlashBag()->add('error', 'Accès refusé : vous devez être administrateur.');
            }

            $response = new RedirectResponse($this->router->generate('login'));
            $event->setResponse($response);
        }
    }
}
