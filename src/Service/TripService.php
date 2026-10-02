<?php

namespace App\Service;

use App\Dto\Trip\TripDetailsOutput;
use App\Dto\Trip\TripListOutput;
use App\Dto\Trip\TripSearchInput;
use App\Entity\Trip;
use App\Exception\Trip\TripNotFoundException;
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

    public function toDetails(Trip $trip): TripDetailsOutput
    {
        return new TripDetailsOutput(
            id: $trip->getId(),
            origin: $this->cityService->toList($trip->getOrigin()),
            destination: $this->cityService->toList($trip->getDestination()),
            departureAt: $trip->getDepartureAt(),
            duration: $trip->getDuration(),
            price: $trip->getPrice(),
            catapultModel: $trip->getCatapultModel()->value,
            boardingInfo: $trip->getBoardingInfo(),
            maxBaggageWeightKg: $trip->getCatapultModel()->maxBaggageWeightKg(),
        );
    }

    /**
    * @throws TripNotFoundException when no trip carries this identifier
    */
    public function findOneById(Uuid $id): Trip
    {
        $trip = $this->tripRepository->find($id);

        if ($trip === null) {
            throw new TripNotFoundException();
        }

        return $trip;
    }

}
