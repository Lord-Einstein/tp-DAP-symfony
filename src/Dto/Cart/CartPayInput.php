<?php

namespace App\Dto\Cart;

use ApiPlatform\Metadata\ApiProperty;
use App\Entity\Enum\CartPayementMethod;
use Symfony\Component\Validator\Constraints as Assert;

final class CartPayInput
{
    public function __construct(

        #[ApiProperty(schema: [
                'description' => "Méthode de paiement déclarée.",
                'type' => 'string',
                'example' => 'card',
                'enum' => ['card', 'voucher'],
            ],
            required: true,
        )]
        #[Assert\NotBlank]
        #[Assert\Choice(callback: [self::class, 'getPaymentMethods'])]
        public readonly string $paymentMethod,

        
    ){}

    public static function getPaymentMethods(): array
    {
        return array_map(fn (CartPayementMethod $method) => $method->value, CartPayementMethod::cases());
    }
}
