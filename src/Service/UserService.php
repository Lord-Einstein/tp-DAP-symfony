<?php

namespace Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Dto\User\UserRegisterInput;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {}

    public function register(UserRegisterInput $input): User 
    {
        $user = new User()
            ->setEmail($input->email)
            ->setFirstName($input->firstName)
            ->setLastName($input->lastName);

        $password = $this->passwordHasher->hashPassword($user, $input->password);
        $user->setPassword($password);

        $this->userRepository->persist($user);
        $this->userRepository->flush();

        return $user;
    }
}
