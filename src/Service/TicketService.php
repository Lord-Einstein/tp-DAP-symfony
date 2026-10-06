<?php

namespace App\Service;

use App\Dto\Ticket\TicketListOutput;
use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Ticket;
use App\Repository\TicketRepository;
use App\Service\Utils\AuditService;

class TicketService
{
    public function __construct(
        private readonly TicketRepository $ticketRepository,
        private readonly TripService $tripService,
        private readonly AuditService $auditService,
    ){}

    /**
     * Builds one ticket per reserved seat of this cart, persisted but not flushed.
     *
     * @return Ticket[]
    */
    public function issue(Cart $cart): array
    {
        $issued = [];

        foreach ($cart->getItems() as $line) {
            $issued = array_merge($issued, $this->issueForCartItem($line));
            // $issued = [...$issued, ...$this->issueForCartItem($line)];
        }

        return $issued;
    }

    private function issueForCartItem(CartItem $cartItem): array
    {
        $issued = [];

        $trip = $cartItem->getTrip();

        for($seat = 0; $seat < $cartItem->getPassengers(); ++$seat) {
            $ticket = new Ticket()
                ->setTrip($trip)
                ->setCart($cartItem->getCart())
                ->setPrice($trip->getPrice());

            $this->auditService->stampCreation($ticket);
            $this->ticketRepository->persist($ticket);

            $issued[] = $ticket;
        }

        return $issued;
    }

    public function toList(Ticket $ticket): TicketListOutput
    {
        return new TicketListOutput(
            id: $ticket->getId(),
            trip: $this->tripService->toList($ticket->getTrip()),
            price: $ticket->getPrice(),
            createdAt: $ticket->getCreatedAt(),

        );
    }

}
