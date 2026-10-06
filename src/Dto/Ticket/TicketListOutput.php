<?php

namespace App\Dto\Ticket;

use ApiPlatform\Metadata\ApiProperty;
use App\Dto\Trip\TripListOutput;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;

final class TicketListOutput
{
    public function __construct(
        
        #[ApiProperty(schema: [
                'description' => 'Identifiant unique du ticket.',
                'type' => 'string',
                'format' => 'uuid',
            ]
        )]
        public readonly Uuid $id,

        #[ApiProperty(schema: [
                'description' => 'Détails du trajet associé au ticket.',
            ]
        )]
        public readonly TripListOutput $trip,

        #[ApiProperty(schema: [
                'description' => 'Nombre de passagers sur ce ticket.',
                'type' => 'id',
            ]
        )]
        public readonly int $price,

        #[ApiProperty(schema: [
                'type' => 'string',
                'format' => 'date-time',
                'description' => 'Date de création.'
            ]
        )]
        public readonly DateTimeImmutable $createdAt,

    ){}
}
