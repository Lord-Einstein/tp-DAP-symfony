<?php

namespace App\DTO\Trip;

use ApiPlatform\Metadata\ApiProperty;
use App\Dto\City\CityListOutput;
use Symfony\Component\Uid\Uuid;

class TripListOutput
{
    public function __construct(

        #[ApiProperty(schema: [
            "type" => "string",
            "description" => "Identifiant du voyage",
            "format" => "uuid",
            "example" => "123e4567-e89b-12d3-a456-426614174000"
        ])]
        public readonly Uuid $id,

        #[ApiProperty(schema: [
            "type" => "object",
            "description" => "Ville de départ",
        ])]
        public readonly CityListOutput $origin,

        #[ApiProperty(schema: [
            "type" => "object",
            "description" => "Ville d'arrivée",
        ])]
        public readonly CityListOutput $destination,

        #[ApiProperty(schema: [
            "type" => "string",
            "description" => "Date de départ",
            "format" => "date-time",
            "example" => "2021-01-01T00:00:00+00:00"
        ])]
        public readonly \DateTimeImmutable $departureAt,

        #[ApiProperty(schema: [
            "type" => "integer",
            "description" => "Durée du voyage en minutes",
            "example" => 60
        ])]
        public readonly int $duration,

        #[ApiProperty(schema: [
            "type" => "integer",
            "description" => "Prix du voyage en centimes",
            "example" => 1000
        ])]
        public readonly int $price,
    ){
    }
}