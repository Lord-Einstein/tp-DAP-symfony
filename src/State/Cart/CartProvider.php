<?php

namespace App\State\Cart;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\Cart;
use App\Service\CartService;


/**
 * Resolves the cart carried by the URL to its entity, so that the operation's
 * security expression has an owner to compare. The entity never leaves: the
 * processor consumes it and returns the DTO.
 * 
 * @implements ProviderInterface<Cart>
 */
final class CartProvider implements ProviderInterface
{
    public function __construct(
        private readonly CartService $cartService,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): null|Cart
    {
        return $this->cartService->findOneById($uriVariables['id']);
    }
}