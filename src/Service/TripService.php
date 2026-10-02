<?php

namespace App\Service;

use App\DTO\Trip\TripListOutput;
use App\Dto\Trip\TripSearchInput;
use App\Entity\Trip;
use App\Repository\TripRepository;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;

class TripService
{
    public function __construct(
        private readonly TripRepository $tripRepository,
        private readonly CityService $cityService,
    )
    {}

    /**
     * @return Trip[]
    */                 
    public function search(TripSearchInput $input): array
    {
        $origin = $this->cityService->findOneById(Uuid::fromString($input->origin));
        $destination = $this->cityService->findOneById(Uuid::fromString($input->destination));

        $date = new DateTimeImmutable($input->date);

        return $this->tripRepository->search($origin, $destination, $date);
    }

    public function toList(Trip $trip): TripListOutput
    {
        return new TripListOutput(
            id: $trip->getId(),
            origin: $this->cityService->toList($trip->getOrigin()),
            destination: $this->cityService->toList($trip->getDestination()),
            departureAt: $trip->getDepartureAt(),
            duration: $trip->getDuration(),
            price: $trip->getPrice(),
        );
    }

}
