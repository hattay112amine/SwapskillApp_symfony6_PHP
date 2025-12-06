<?php
// src/Security/AdminAccessDeniedHandler.php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class AdminAccessDeniedHandler implements AccessDeniedHandlerInterface
{
    private RouterInterface $router;
    private RequestStack $requestStack;

    public function __construct(RouterInterface $router, RequestStack $requestStack)
    {
        $this->router = $router;
        $this->requestStack = $requestStack;
    }

    public function handle(Request $request, AccessDeniedException $accessDeniedException): RedirectResponse
    {
        $session = $this->requestStack->getSession();
        if ($session) {
            $session->getFlashBag()->add('error', 'Accès refusé : vous devez être administrateur!');
        }

        return new RedirectResponse($this->router->generate('login'));
    }
}
