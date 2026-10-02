<?php

namespace App\Dto\Trip;

use ApiPlatform\Metadata\ApiProperty;
use App\Dto\City\CityListOutput;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;

final class TripDetailsOutput extends TripListOutput
{
    public function __construct(
        Uuid $id,
        CityListOutput $origin,
        CityListOutput $destination,
        DateTimeImmutable $departureAt,
        int $duration,
        int $price,

        #[ApiProperty(
            description: 'Modèle de catapulte utilisé pour le trajet.'
        )]
        public readonly string $catapultModel,

        #[ApiProperty(
            description: 'Consignes et informations d\'embarquement.'
        )]
        public readonly string $boardingInfo,

        #[ApiProperty(
            description: 'Poids maximum de bagage autorisé en kg.'
        )]
        public readonly int $maxBaggageWeightKg,
    ) {
        parent::__construct(
            id: $id,
            origin: $origin,
            destination: $destination,
            departureAt: $departureAt,
            duration: $duration,
            price: $price,
        );
    }
}