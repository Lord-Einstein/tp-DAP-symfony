<?php

namespace App\Service;

use App\Dto\City\CityListOutput;
use App\Entity\City;
use App\Repository\CityRepository;

class CityService
{
    private const MAX_RESULTS = 100;
    private const DEFAULT_LIMIT = 20;

    public function __construct(
        private readonly CityRepository $cityRepository
    )
    {

    }

    public function toList(City $city) : CityListOutput {
        return new CityListOutput(
            id: $city->getId(),
            name: $city->getName(),
        );
    }

    public function search(?string $query = null, ?int $limit = 20): array
    {
        if(trim($query) === ''){
            $query = null;
        }

        $limit = min(self::MAX_RESULTS, max(1, $limit ?? self::DEFAULT_LIMIT));

        return $this->cityRepository->search($query, $limit);
    }

}