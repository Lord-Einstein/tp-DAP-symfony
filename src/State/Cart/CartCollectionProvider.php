<?php

namespace App\State\Cart;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Dto\Cart\CartDetailsOutput;
use App\Entity\User;
use App\Service\CartService;
use Symfony\Bundle\SecurityBundle\Security;

class CartCollectionProvider implements ProviderInterface
{
    public function __construct(
        public readonly CartService $cartService,
        public readonly Security $security,
    ){}

    /**
     * Serves the cart collection.
     *
     * @return CartDetailsOutput[]
     */
    public function provide(
        Operation $operation, 
        array $uriVariables = [], 
        array $context = []
    ): array {

        $user = $this->security->getUser();
        if(!$user instanceof User) {
            return [];
        }

        $carts = $this->cartService->findActiveFor($user);
        if($carts === null) {
            return [];
        }
        return array_map($this->cartService->toDetails(...), [$carts]);
    }
}
