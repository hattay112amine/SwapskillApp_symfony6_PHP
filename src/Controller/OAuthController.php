<?php

namespace App\Controller;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OAuthController extends AbstractController
{
    #[Route('/connect/google', name: 'connect_google_start')]
    public function connectGoogle(ClientRegistry $clientRegistry)
    {
        // Redirect to Google OAuth
        return $clientRegistry
            ->getClient('google')
            ->redirect(['email', 'profile']); // scopes
    }

    #[Route('/connect/facebook', name: 'connect_facebook_start')]
    public function connectFacebook(ClientRegistry $clientRegistry)
    {
        // Redirect to Facebook OAuth
        return $clientRegistry
            ->getClient('facebook')
            ->redirect(['email']); // scopes
    }

    // The "check" routes will be handled automatically by Symfony's security system
}
