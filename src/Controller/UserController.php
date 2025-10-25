<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class UserController extends AbstractController
{
    #[Route('/profile/dashboard', name: 'user_dashboard')]
    public function dashboard(): Response
    {
        return $this->render('home/user_dashboard.html.twig');
    }
}