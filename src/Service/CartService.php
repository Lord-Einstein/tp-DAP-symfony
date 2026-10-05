<?php

namespace App\Service;

use App\Dto\Cart\CartDetailsOutput;
use App\Dto\Cart\CartLineOutput;
use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\User;
use App\Repository\CartRepository;
use App\Service\Utils\AuditService;

class CartService
{
    public function __construct(
        private readonly CartRepository $cartRepository,
        private readonly TripService $tripService,
        private readonly AuditService $auditService,
    ){}

    public function toLine(CartItem $item): CartLineOutput
    {
        return new CartLineOutput(
            id: $item->getId(),
            trip: $this->tripService->toList($item->getTrip()),
            passengers: $item->getPassengers(),
            subtotal: $item->getTrip()->getPrice() * $item->getPassengers(),
        );
    }

    public function toDetails(Cart $cart): CartDetailsOutput
    {
        $lines = array_map($this->toLine(...), $cart->getItems()->toArray());

        return new CartDetailsOutput(
            id: $cart->getId(),
            status: $cart->getStatus(),
            items: $lines,

            // total: array_reduce(
            //     $cart->getItems()->toArray(),
            //     (int $total, CartItem $item) 
            //         => $total + $item->getTrip()->getPrice() * $item->getPassengers()
            // ),
            
            total: array_sum(array_column($lines, 'subtotal')),
            createdAt: $cart->getCreatedAt(),
        );
    }

    public function findActiveFor(User $user): ?Cart
    {
        return $this->cartRepository->findActiveFor($user);
    }

    public function open(User $user): Cart
    {
        $existing = $this->findActiveFor($user);
        if($existing !== null) {
            return $existing;
        }
       
        $cart = new Cart();

        $this->auditService->stampCreation($cart);

        $this->cartRepository->persist($cart);
        $this->cartRepository->flush();

        return $cart;
    }
}
