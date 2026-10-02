<?php

namespace App\State\Trip;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Dto\Trip\TripDetailsOutput;
use App\Service\TripService;
use Symfony\Component\Uid\Uuid;

final class TripItemProvider implements ProviderInterface
{
    public function __construct(
        private readonly TripService $tripService,
    ){}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): ?TripDetailsOutput
    {
        $trip = $this->tripService->findOneById(Uuid::fromString($uriVariables['id']));
        if ($trip === null) {
            return null;
        }
        return $this->tripService->toDetails($trip);
    }
}
