<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\QueryParameter;
use App\Dto\City\CityListOutput;
use App\Entity\Impl\AbstractEntity;
use App\Repository\CityRepository;
use App\State\City\CityCollectionProvider;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;


#[ApiResource(
    // GET /api/cities
    operations: [
        new GetCollection(
            provider: CityCollectionProvider::class,
            output:CityListOutput::class,
            paginationEnabled: false,
            parameters: [
                'q' => new QueryParameter(
                    description: 'Filtre textuel sur le nom de la ville, Insensible à la casse et aux accents.',
                    schema: [
                        'type' => 'string'
                    ],
                ),
                'limit' => new QueryParameter(
                    description: 'Nombre maximum de villes retournées.',
                    schema: [
                        'type' => 'integer',
                        'minimum' => 1,
                        'maximum' => 100,
                        'default' => 20,
                    ],
                )
            ]
        ),
    ]
)]
#[ORM\Entity(repositoryClass: CityRepository::class)]
class City extends AbstractEntity
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    private Uuid $id;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    public function __construct()
    {
        $this->id = Uuid::v7();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }
}
