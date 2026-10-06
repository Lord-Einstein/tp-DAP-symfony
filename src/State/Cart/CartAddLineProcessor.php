<?php

namespace App\State\Cart;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Dto\Cart\CartAddLineInput;
use App\Dto\Cart\CartDetailsOutput;
use App\Service\CartService;

/**
 * @implements ProcessorInterface<CartAddLineInput, CartDetailsOutput>
 */
final class CartAddLineProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly CartService $cartService,
    ){}
    
    /**
     * @param CartAddLineInput $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): CartDetailsOutput
    {
        $cart = $this->cartService->findOneById($uriVariables['id']);

        $updatedCard = $this->cartService->addLine($cart, $data);

        return $this->cartService->toDetails($updatedCard);
    }
    
}
