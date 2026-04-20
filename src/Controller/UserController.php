<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/user')]
final class UserController extends AbstractController
{
    #[Route('/account', name: 'app_user_account', methods: ['GET'])]
    public function account(): Response
    {
        $currentUser = $this->getUser();

        return $this->render('user/account.html.twig', [
            'user' => $currentUser,
        ]);
    }
}
