<?php

namespace Dto\User;

use Symfony\Component\Validator\Constraints as Assert;

class UserRegisterInput
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Email]
        #[Assert\Length(min:3, max:255)]
        public string $email,

        #[Assert\NotBlank]
        #[Assert\Length(min:8, max:255)]
        #[Assert\PasswordStrength]
        public string $password,

        #[Assert\NotBlank(allowNull: true)]
        #[Assert\Length(min:3, max:255)]
        public ?string $firstName = null,

        #[Assert\NotBlank(allowNull: true)]
        #[Assert\Length(min:3, max:255)]
        public ?string $lastName = null,
    ){}
}
