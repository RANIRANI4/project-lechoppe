<?php

namespace App\DataFixtures;

use App\Entity\Shop;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class ShopFixtures extends Fixture implements DependentFixtureInterface
{
    public const SHOP_REFERENCE = 'shop';

    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < UserFixtures::PRODUCER_COUNT; $i++) {
            $shop = new Shop();
            $shop->setName('Ferme ' . $this->faker->lastName());
            $shop->setDescription($this->faker->paragraph(4));
            $shop->setAddress($this->faker->streetAddress());
            $shop->setZipCode($this->faker->postcode());
            $shop->setCity($this->faker->city());
            $shop->setLatitude($this->faker->latitude(43.20, 43.40));
            $shop->setLongitude($this->faker->longitude(5.20, 5.55));
            $shop->setImageFileName('shopfixtures.jpg');
            $shop->setProducer($this->getReference(UserFixtures::PRODUCER_REFERENCE . $i, User::class));

            $manager->persist($shop);
            $this->addReference(self::SHOP_REFERENCE . $i, $shop);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [UserFixtures::class];
    }
}
