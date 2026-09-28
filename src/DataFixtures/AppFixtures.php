<?php

namespace App\DataFixtures;

use App\Entity\City;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    private const PLAIN_PASSWORD = 'motdepasse';

    public function __construct(
        private readonly UserPasswordHasherInterface $hasher
    ) {}

    public function load(ObjectManager $manager): void
    {

        // #region Users
        $alice = new User()
            ->setEmail('alice@example.fr')
            ->setCreatedAt(new DateTimeImmutable());

        $alice->setPassword($this->hasher->hashPassword($alice, self::PLAIN_PASSWORD));
        $manager->persist($alice);

        $bob = new User()
            ->setEmail('bob@example.fr')
            ->setCreatedAt(new DateTimeImmutable());

        $bob->setPassword($this->hasher->hashPassword($bob, self::PLAIN_PASSWORD));
        $manager->persist($bob);

        $camille = new User()
            ->setEmail('camille.aubert@example.fr')
            ->setFirstName('Camille')
            ->setLastName('Aubert')
            ->setCreatedAt(new DateTimeImmutable('2026-02-04'));

        $camille->setPassword($this->hasher->hashPassword($camille, self::PLAIN_PASSWORD));
        $manager->persist($camille);

        // #endregion

        // #region Cites
        $cities = [
            'Paris',
            'Lyon',
            'Marseille',
            'Bordeaux',
            'Lille',
            'Strasbourg',
            'Toulouse',
            'Nantes',
            'Dijon',
            'Brest',
        ];

        foreach ($cities as $cityName) {
            $city = (new City())
                ->setName($cityName)
                ->setCreatedAt(new DateTimeImmutable());

            $manager->persist($city);
        }
        //endregion

        $manager->flush();

    }
}
