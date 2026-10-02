<?php

namespace App\Dto\Trip;

use ApiPlatform\Metadata\ApiProperty;
use Symfony\Component\Validator\Constraints as Assert;

final class TripSearchInput
{
    public function __construct(

        #[Assert\NotBlank]
        #[Assert\Uuid]
        #[ApiProperty(schema: [
            'type' => 'string',
            'format' => 'uuid', 
            'description' => 'Uuid de le ville d\'origine',
        ], required: true)]
        public ?string $origin = null,
        
        #[Assert\NotBlank]
        #[Assert\Uuid]
        #[ApiProperty(schema: [
            'type' => 'string',
            'format' => 'uuid', 
            'description' => 'Uuid de la ville de destination',
        ], required: true)]
        public ?string $destination = null,

        #[Assert\NotBlank]
        #[Assert\Date]
        #[ApiProperty(schema: [
            'type' => 'string',
            'format' => 'date', 
            'description' => 'Date de départ',
        ], required: true)]
        public ?string $date = null,

        #[Assert\NotBlank]
        #[Assert\Positive]
        #[ApiProperty(schema: [
            'type' => 'integer',
            'description' => 'Nombre de passagers',
        ], required: true)]
        public ?int $passengers = null,

    )
    {}
}
