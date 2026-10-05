<?php

namespace App\Service;

use App\Dto\Cart\CartAddLineInput;
use App\Dto\Cart\CartDetailsOutput;
use App\Dto\Cart\CartLineOutput;
use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\User;
use App\Exception\Cart\CartNotFoundException;
use App\Exception\Trip\TripNotFoundException;
use App\Repository\CartRepository;
use App\Service\Utils\AuditService;
use Symfony\Component\Uid\Uuid;

class CartService
{
    public function __construct(
        private readonly CartRepository $cartRepository,
        private readonly TripService $tripService,
        private readonly AuditService $auditService,
    ) {}

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
        if ($existing !== null) {
            return $existing;
        }

        $cart = new Cart();

        $this->auditService->stampCreation($cart);

        $this->cartRepository->persist($cart);
        $this->cartRepository->flush();

        return $cart;
    }

    /**
     * Returns the cart carrying this identifier.
     *
     * @throws CartNotFoundException when no cart carries this identifier
     */
    public function findOneById(Uuid $id): Cart
    {
        $cart = $this->cartRepository->find($id);

        if ($cart === null) {
            throw new CartNotFoundException();
        }
        
        return $cart;
    }

    /**
     * Adds a line to this cart and returns the cart itself.
     *
     * @throws TripNotFoundException when no trip carries the submitted identifier
     */
    public function addLine(Cart $cart, CartAddLineInput $input): Cart 
    {
        
        $trip = $this->tripService->findOneById(Uuid::fromString($input->tripId));

        $cartItem = new CartItem()
            ->setTrip($trip)
            ->setPassengers($input->passengers);

        $this->auditService->stampCreation($cartItem);
        
        $cart->addItem($cartItem);

        //pas de flush ici à cause du cascade persist
        $this->cartRepository->flush();

        return $cart;
    }
}
