<?php

namespace App\Dto\Cart;

use ApiPlatform\Metadata\ApiProperty;
use App\Entity\Enum\CartStatus;
use Symfony\Component\Uid\Uuid;

final class CartDetailsOutput
{
    /**
     * @param CartLineOutput[] $items
     */
    public function __construct(
        #[ApiProperty(schema: [
                'description' => "Identifiant du panier.",
                'type' => 'string', 
                'format' => 'uuid', 
            ]
        )]
        public readonly Uuid $id,

        #[ApiProperty(schema: [
                'description' => "Statut du panier.",
                'type' => 'string',
                'enum' => [
                    'pending' => 'En attente de paiement',
                    'paid' => 'Payé',
                ],
            ]
        )]
        public readonly CartStatus $status,

        #[ApiProperty(description : "Lignes du panier.")]
        /**
         * @var CartLineOutput[] $items
         */
        public readonly array $items,

        #[ApiProperty(schema: [
                'description' => "Sommes des sous-totals des lignes du panier (en centimes.)",
                'type' => 'integer',
            ]
        )]
        public readonly int $total,

        #[ApiProperty(schema: [
                'description' => "Date de création du panier.",
                'type' => 'string',
                'format' => 'date-time',
            ]
        )]
        public readonly \DateTimeImmutable $createdAt,
    ) {}
}