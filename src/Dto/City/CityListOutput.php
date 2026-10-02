<?php

namespace App\Dto\City;

use ApiPlatform\Metadata\ApiProperty;

final class CityListOutput
{
    public function __construct(
        #[ApiProperty(
            schema:[
                'description' => "Identifiant unique de la ville.", 
                'type' => 'string',
                'format' => 'uuid'
            ],
        )]
        public string $id,
        
        #[ApiProperty(
            description:"Nom de la ville."
        )]
        public string $name,
    ) {}

}