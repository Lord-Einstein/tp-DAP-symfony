<?php

namespace App\State\City;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Dto\City\CityListOutput;
use App\Service\CityService;

final class CityCollectionProvider implements ProviderInterface
{
    // le service n'est pas construit ici, il est demandé au conteneur
    public function __construct(
        private readonly CityService $cityService,
    ) {}

    /**
     * Serves the city collection, already mapped onto its output payload.
     *
     * @return CityListOutput[]
     */
    public function provide(
        Operation $operation, 
        array $uriVariables = [], 
        array $context = []
    ): array {

        //récup les paramètres
        $filters = $context['filters'] ?? [];

        $query = trim($filters['q'] ?? '');
        $limit = (int) ($filters['limit'] ?? $this->cityService::DEFAULT_LIMIT);

        $cities = $this->cityService->search($query, $limit);

        return array_map($this->cityService->toList(...), $cities);
        // return array_map(fn($city) => $this->cityService->toList(...), $cities);
    }
}
