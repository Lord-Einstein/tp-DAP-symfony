<?php

namespace App\Dto\Cart;

use ApiPlatform\Metadata\ApiProperty;
use App\Dto\Trip\TripListOutput;
use Symfony\Component\Uid\Uuid;

final class CartLineOutput
{
    public function __construct(
        #[ApiProperty(schema: [
                'description' => "Identifiant de la ligne.",
                'type' => 'string', 
                'format' => 'uuid', 
            ]
        )]
        public readonly Uuid $id,

        #[ApiProperty(description: "Détails de la ligne.")]
        public readonly TripListOutput $trip,

        #[ApiProperty(
            schema: [
                'description' => "Nombre de passagers.",
                'type' => 'integer',
            ]
        )]
        public readonly int $passengers,
        
         #[ApiProperty(schema: [
                'description' => "Prix du lancer multiplié par le nombre de places, en centimes.",
                'type' => 'integer',
            ]
        )]
        public readonly int $subtotal,
    ) {}
}