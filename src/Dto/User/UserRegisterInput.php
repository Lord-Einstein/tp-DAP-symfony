<?php

namespace App\Dto\User;


use ApiPlatform\Metadata\ApiProperty;
use Symfony\Component\Validator\Constraints as Assert;

final class UserRegisterInput
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Email]
        #[Assert\Length(min:3, max:255)]
        #[ApiProperty(schema: [
                'type' => 'string',
                'format' => 'email',
                'description' => "L'email de l'utilisateur (doit être unique).",
                'minLength' => 3,
                'maxLength' => 255,
                'example' => "user@example.com",
            ],
            required: true
        )]
        public string $email,

        #[Assert\NotBlank]
        #[Assert\Length(min:8, max:255)]
        #[Assert\PasswordStrength]
        #[ApiProperty(schema: [
                'type' => 'integer',
                'format' => 'password',
                'description' => "Le mot de passe de l'utilisateur (doit etre sécurisé).",
                'minLength' => 8,
                'maxLength' => 255,
                'example' => "SuperMegaMotDEP4SSE.",
            ],
            required: true
        )]
        public string $password,

        #[Assert\NotBlank(allowNull: true)]
        #[Assert\Length(min:3, max:255)]
        #[ApiProperty(schema: [
                'type' => 'string',
                'description' => "Le prénom de l'utilisateur.",
                'minLength' => 3,
                'maxLength' => 255,
                'example' => "Alex",
            ],
        )]
        public ?string $firstName = null,

        #[Assert\NotBlank(allowNull: true)]
        #[Assert\Length(min:3, max:255)]
        #[ApiProperty(schema: [
                'type' => 'string',
                'description' => "Le prénom de l'utilisateur.",
                'minLength' => 3,
                'maxLength' => 255,
                'example' => "DUPONT",
            ],
        )]
        public ?string $lastName = null,

    ){}
}
