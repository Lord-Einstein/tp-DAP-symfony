<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class DebugUserController extends AbstractController
{
    
    public function __construct(
        private readonly UserRepository $userRepository
    ) {

    }

    #[Route('/_debug/users', name: 'app_debug_users', methods: ['GET'])]
    public function __invoke(): JsonResponse
    {
        return $this->json(
            $this->userRepository->findAll()
        );
    }
}