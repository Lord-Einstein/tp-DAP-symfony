<?php

namespace App\Dto\Cart;

use ApiPlatform\Metadata\ApiProperty;
use Symfony\Component\Validator\Constraints as Assert;

class CartAddLineInput
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        #[ApiProperty(schema: [
            'description' => "Identifiant du trajet à ajouter.",
            'type' => 'string',
            'format' => 'uuid',
            ],
            required: true,
        )]
        public readonly string $tripId,

        #[Assert\NotBlank]
        #[Assert\Positive]
        #[ApiProperty(schema: [
            'description' => "Nombre de passagers.",
            'type' => 'integer',
            ],
            required: true,
        )]
        public readonly int $passengers,
    ){}
}
