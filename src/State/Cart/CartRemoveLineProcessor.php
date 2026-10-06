<?php

namespace App\State\Cart;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Service\CartService;
use Symfony\Component\Uid\Uuid;

class CartRemoveLineProcessor implements ProcessorInterface
{
    public function __construct(
        private CartService $cartService,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        $cart = $data;
        $lineId = Uuid::fromString($uriVariables['itemId']);
        $this->cartService->removeLine($cart, $lineId);
        
        return null;
    }
}