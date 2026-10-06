<?php

namespace App\State\Cart;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Cart;
use App\Entity\User;
use App\Service\CartService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @implements ProcessorInterface<Cart, null>
 */
final class CartRemoveLineProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly CartService $cartService,
        private readonly Security $security,
    ){}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): null
    {
        $user = $this->security->getUser();

        if(!$user instanceof User) {
            throw new AccessDeniedException();
        }

        $cart = $this->cartService->findOneById($uriVariables['id']);
        $this->cartService->removeLine($cart, $uriVariables['itemId']);

        return null;
        
    }
    
}
