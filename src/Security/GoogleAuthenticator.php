<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Security\Authenticator\OAuth2Authenticator;
use League\OAuth2\Client\Provider\GoogleUser;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;

class GoogleAuthenticator extends OAuth2Authenticator
{
    private ClientRegistry $clientRegistry;
    private EntityManagerInterface $em;
    private UserRepository $userRepository;
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(
        ClientRegistry $clientRegistry,
        EntityManagerInterface $em,
        UserRepository $userRepository,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->clientRegistry = $clientRegistry;
        $this->em = $em;
        $this->userRepository = $userRepository;
        $this->urlGenerator = $urlGenerator;
    }

    public function supports(Request $request): bool
    {
        // This route must match your Google "check" route
        return $request->attributes->get('_route') === 'connect_google_check';
    }

    public function authenticate(Request $request): Passport
    {
        $client = $this->clientRegistry->getClient('google');
        $accessToken = $this->fetchAccessToken($client);
        /** @var GoogleUser $googleUser */
        $googleUser = $client->fetchUserFromToken($accessToken);

        $googleId = $googleUser->getId();
        $email = $googleUser->getEmail();
        $name = $googleUser->getName();

        return new SelfValidatingPassport(
            new UserBadge($email, function ($userIdentifier) use ($email, $googleId, $name) {
                $user = $this->userRepository->findOneBy(['googleId' => $googleId]);

                if (!$user) {
                    $user = $this->userRepository->findOneBy(['email' => $email]);
                }

                if (!$user) {
                    $user = new User();
                    $user->setEmail($email);
                    $user->setName($name);
                    $user->setGoogleId($googleId);
                    $user->setRoles(['ROLE_USER']);
                    $user->setCreatedAt(new \DateTime());
                    $user->setStatus(true);
                    $user->setPassword('');
                    $user->setPhone('');
                    $this->em->persist($user);
                    $this->em->flush();
                }

                if (!$user->getGoogleId()) {
                    $user->setGoogleId($googleId);
                    $this->em->flush();
                }

                return $user;
            })
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?RedirectResponse
    {
        // Redirect to homepage or dashboard
        return new RedirectResponse($this->urlGenerator->generate('homepage'));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?RedirectResponse
    {
        // Redirect to login page with error message
        return new RedirectResponse($this->urlGenerator->generate('login'));
    }
}
