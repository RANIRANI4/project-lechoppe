<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public const PRODUCER_REFERENCE = 'producer';
    public const PRODUCER_COUNT = 10;

    private Generator $faker;

    public function __construct(
        private readonly UserPasswordHasherInterface $hasher
    ) {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < self::PRODUCER_COUNT; $i++) {
            $user = new User();
            $user->setFirstName($this->faker->firstName());
            $user->setLastName($this->faker->lastName());
            $user->setEmail("producteur$i@echoppe.fr");
            $user->setPassword($this->hasher->hashPassword($user, 'password'));
            $user->setRoles([]);
            $user->setCreatedAt(new \DateTime());
            $user->setUpdatedAt(new \DateTime());

            $manager->persist($user);
            $this->addReference(self::PRODUCER_REFERENCE . $i, $user);
        }

        $manager->flush();
    }
}
