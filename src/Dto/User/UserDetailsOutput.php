<?php

namespace Dto\User;

use ApiPlatform\Metadata\ApiProperty;
use DateTimeImmutable;

class UserDetailsOutput
{
    public function __construct(
        #[ApiProperty(
            schema: [
                'description' => "Identifiant unique de l'utilisateur.",
                'type' => 'string',
                'format' => 'uuid'
            ],
            required: true,
        )]
        public string $id,

        #[ApiProperty(
            schema: [
                'description' => "Email de l'utilisateur (doit aussi être unique).",
                'type' => 'string',
                'format' => 'email',
                'minLength' => 3,
                'maxLength' => 255,
                'example' => "user@example.com",
            ],
            required: true,
        )]
        public string $email,

        #[ApiProperty(
            schema: [
                'description' => "Le mot de passe de l'utilisateur (doit etre sécurisé).",
                'type' => 'string',
                'format' => 'password',
                'minLength' => 8,
                'maxLength' => 255,
                'example' => "SuperMegaMotDEP4SSE.",
            ],
            required: true,
        )]
        public string $password,

        #[ApiProperty(
            schema: [
                'description' => "Date de création du dossier utilisateur.",
                'type' => 'string',
                'format' => 'date-time',
                'example' => "2022-01-01T00:00:00+00:00",
            ],
        )]
        public DateTimeImmutable $createdAt,

        #[ApiProperty(
            schema: [
                'description' => "Le prénom de l'utilisateur.",
                'type' => 'string',
                'minLength' => 3,
                'maxLength' => 255,
                'example' => "Alex",
            ],
        )]
        public ?string $firstName = null,

        #[ApiProperty(
            schema: [
                'description' => "Le nom de famille de l'utilisateur.",
                'type' => 'string',
                'minLength' => 3,
                'maxLength' => 255,
                'example' => "DUPONT",
            ],
        )]
        public ?string $lastName = null,

    ) {}
}
