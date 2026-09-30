<?php

namespace App\State\User;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Dto\User\UserDetailsOutput;
use App\Service\UserService;

final class UserRegisterProcessor implements ProcessorInterface
{
    
    public function __construct(
        private readonly UserService $userService,
    ){}

    /**
     * Serves the city collection, already mapped onto its output payload.
     *
     * @return UserDetailsOutput
     */
    public function process(
        mixed $data, 
        Operation $operation, 
        array $uriVariables = [], 
        array $context = []
    ): UserDetailsOutput {

        $user = $this->userService->register($data);
        $output = $this->userService->toDetails($user);
        
        return $output;
    }  
    
}


