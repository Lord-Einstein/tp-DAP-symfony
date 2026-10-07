<?php

namespace App\Dto\Cart;

use ApiPlatform\Metadata\ApiProperty;
use App\Dto\Ticket\TicketListOutput;

class CartPayOutput
{
    public function __construct(

        #[ApiProperty(schema: [
                'description' => "Confirmation du paiement.",
                'type' => 'string',
                'example' => 'ONTB-2026-4F8A21',
            ],
        )]
        public readonly string $confirmation,


        #[ApiProperty(description : "Tickets émis par le paiement, un par ligne.")]
        /**
         * @var TicketListOutput[] $tickets
         */
        public readonly array $tickets,

        
    ){}
}
