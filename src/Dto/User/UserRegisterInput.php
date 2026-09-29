<?php

namespace Dto\User;

use ApiPlatform\Metadata\ApiProperty;
use Symfony\Component\Validator\Constraints as Assert;

class UserRegisterInput
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Email]
        #[Assert\Length(min:3, max:255)]
        #[ApiProperty(schema: [
                'type' => 'string',
                'format' => 'email',
                'description' => "L'email de l'utilisateur (doit être unique).",
                'minLenght' => 3,
                'maxLenght' => 255,
                'example' => "user@example.com",
                'required' => true,
            ],
        )]
        public string $email,

        #[Assert\NotBlank]
        #[Assert\Length(min:8, max:255)]
        #[Assert\PasswordStrength]
        #[ApiProperty(schema: [
                'type' => 'integer',
                'format' => 'password',
                'description' => "Le mot de passe de l'utilisateur (doit etre sécurisé).",
                'minLenght' => 8,
                'maxLenght' => 255,
                'example' => "SuperMegaMotDEP4SSE.",
                'required' => true,
            ],
        )]
        public string $password,

        #[Assert\NotBlank(allowNull: true)]
        #[Assert\Length(min:3, max:255)]
        #[ApiProperty(schema: [
                'type' => 'string',
                'description' => "Le prénom de l'utilisateur.",
                'minLenght' => 3,
                'maxLenght' => 255,
                'example' => "Alex",
                'required' => false,
            ],
        )]
        public ?string $firstName = null,

        #[Assert\NotBlank(allowNull: true)]
        #[Assert\Length(min:3, max:255)]
        #[ApiProperty(schema: [
                'type' => 'string',
                'description' => "Le prénom de l'utilisateur.",
                'minLenght' => 3,
                'maxLenght' => 255,
                'example' => "DUPONT",
                'required' => false,
            ],
        )]
        public ?string $lastName = null,

    ){}
}
