<?php

namespace App\Dto\Cart;

use ApiPlatform\Metadata\ApiProperty;

class CartAddLineInput
{
    public function __construct(
        #[ApiProperty(schema: [
            'description' => "Identifiant du trajet.",
            'type' => 'string',
            'format' => 'uuid',
            ],
            required: true,
        )]
        public readonly string $tripId,

        #[ApiProperty(schema: [
            'description' => "Nombre de passagers.",
            'type' => 'integer',
            ],
            required: true,
        )]
        public readonly int $passengers,
    ){}
}
