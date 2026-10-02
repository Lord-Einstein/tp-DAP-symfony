<?php

namespace App\Service;

use App\DTO\City\CityListOutput;
use App\Entity\City;
use App\Exception\City\CityNotFoundException;
use App\Repository\CityRepository;
use Symfony\Component\Uid\Uuid;

class CityService
{
    private const MAX_RESULTS = 100;
    public const DEFAULT_LIMIT = 20;

    public function __construct(
        private readonly CityRepository $cityRepository,
    ) {}

    public function toList(City $city): CityListOutput
    {
        return new CityListOutput(id: $city->getId(), name: $city->getName());
    }

    public function search(?string $query = null, ?int $limit = null): array
    {
        $query = trim($query);

        if ($query === "") {
            $query = null;
        }
        $limit = min(self::MAX_RESULTS, max(1, $limit ?? self::DEFAULT_LIMIT));

        return $this->cityRepository->search($query, $limit);
    }

    /**
     * @throws CityNotFoundException when no city carries this identifier
     */
    public function findOneById(Uuid $id): City
    {
        $found = $this->cityRepository->findOneById($id);

        if (!$found) {
            throw new CityNotFoundException();
        }

        return $found;
    }
}