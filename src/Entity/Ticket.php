<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\Dto\Ticket\TicketListOutput;
use App\Entity\Impl\AbstractEntity;
use App\Repository\TicketRepository;
use App\State\Ticket\TicketCollectionProvider;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: TicketRepository::class)]

#[ApiResource( operations: [
        new Get(
            uriTemplate: '/tickets',
            input: false,
            output: TicketListOutput::class,
            provider: TicketCollectionProvider::class,
            security: "is_granted('ROLE_USER')",
        ),
    ]
)]

class Ticket extends AbstractEntity
{
    #[ORM\Id]
    #[ORM\Column (
        type: UuidType::NAME,
        unique: true,
    )]
    private Uuid $id;

    #[ORM\ManyToOne(targetEntity: Trip::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Trip $trip;

    #[ORM\ManyToOne(targetEntity: Cart::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Cart $cart;

    /**
     * @var int $price The price of this ticket, in cents.
     */
    #[ORM\Column(type: Types::INTEGER)]
    private int $price;

    public function __construct()
    {
        $this->id = Uuid::v7();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getTrip(): Trip
    {
        return $this->trip;
    }

    public function setTrip(Trip $trip): static
    {
        $this->trip = $trip;

        return $this;
    }

    public function getCart(): Cart
    {
        return $this->cart;
    }

    public function setCart(Cart $cart): static
    {
        $this->cart = $cart;

        return $this;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }
}
