<?php

namespace App\Service;

use App\Dto\Cart\CartAddLineInput;
use App\Dto\Cart\CartDetailsOutput;
use App\Dto\Cart\CartLineOutput;
use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Enum\CartStatus;
use App\Entity\Ticket;
use App\Entity\User;
use App\Exception\Cart\CartAlreadyPaidException;
use App\Exception\Cart\CartEmptyException;
use App\Exception\Cart\CartLineNotFoundException;
use App\Exception\Cart\CartNotFoundException;
use App\Exception\Trip\TripNotFoundException;
use App\Repository\CartRepository;
use App\Service\Utils\AuditService;
use App\Dto\Cart\CartPayOutput;
use Symfony\Component\Uid\Uuid;

class CartService
{
    public function __construct(
        private readonly CartRepository $cartRepository,
        private readonly TripService $tripService,
        private readonly AuditService $auditService,
        private readonly TicketService $ticketService,
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
     * @throws CartAlreadyPaidException when the cart is already paid
     */
    public function addLine(Cart $cart, CartAddLineInput $input): Cart
    {
        if ($cart->getStatus() === CartStatus::Paid) {
            throw new CartAlreadyPaidException();
        }

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

    /**
     * Soft-deletes a line of this cart, and the cart itself when it was the last one.
     *
     * @throws CartAlreadyPaidException  when the cart is no longer modifiable
     * @throws CartLineNotFoundException when this cart carries no such line
    */
    public function removeLine(Cart $cart, Uuid $lineId): void
    {
        if ($cart->getStatus() === CartStatus::Paid) {
            throw new CartAlreadyPaidException();
        }

        $line = $cart
            ->getItems()
            ->findFirst(
                static fn($_, CartItem $item): bool  => $item->getId()->equals($lineId),
            );

        if ($line === null) {
            throw new CartLineNotFoundException();
        }

        $this->auditService->markDeleted($line);

        $alive = $cart
            ->getItems()
            ->filter(
                static fn(CartItem $item): bool  => $item->getDeletedAt() === null,
            );
            
        if($alive->isEmpty()) {
            $this->auditService->markDeleted($cart);
        }

        $this->cartRepository->flush();
    }

    /**
     * Pays this cart: issues its tickets and marks it as paid, in a single write.
     *
     * @return Ticket[] the tickets issued
     *
     * @throws CartAlreadyPaidException when the cart has already been paid
     * @throws CartEmptyException       when the cart carries no line
    */
    public function pay(Cart $cart): array
    {
        if ($cart->getStatus() === CartStatus::Paid) {
            throw new CartAlreadyPaidException();
        }

        if($cart->getItems()->isEmpty()) {
            throw new CartEmptyException();
        }

        $tickets = $this->ticketService->issue($cart);

        $cart->setStatus(CartStatus::Paid);

        $this->auditService->stampUpdate($cart);

        $this->cartRepository->flush();

        return $tickets;
    }

    /**
     * Returns the confirmation reference of this cart, derived from its identity.
    */
    public function confirmationOf(Cart $cart): string
    {
        $uuidString = $cart->getId()->toRfc4122();
        $lastSix = strtoupper(substr($uuidString, -6));
        $year = $cart->getCreatedAt()->format('Y');

        return sprintf('ONTB-%s-%s', $year, $lastSix);
    }

    /**
     * Maps a paid cart and its tickets onto the payload served by the payment endpoint.
     *
     * @param Ticket[] $tickets
    */
    public function toPayment(Cart $cart, array $tickets): CartPayOutput
    {
        return new CartPayOutput(
            confirmation: $this->confirmationOf($cart),
            tickets: array_map($this->ticketService->toList(...), $tickets),
        );
    }
}
