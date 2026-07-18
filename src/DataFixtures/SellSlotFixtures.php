<?php

namespace App\DataFixtures;

use App\Entity\SellSlot;
use App\Entity\Shop;
use App\Enum\EnumState;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class SellSlotFixtures extends Fixture implements DependentFixtureInterface
{
    public const SLOT_REFERENCE = 'slot';
    public const SLOTS_PER_SHOP = 5;

    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < UserFixtures::PRODUCER_COUNT; $i++) {
            $shop = $this->getReference(ShopFixtures::SHOP_REFERENCE . $i, Shop::class);

            for ($j = 0; $j < self::SLOTS_PER_SHOP; $j++) {
                $startDate = $this->faker->dateTimeBetween('now', '+3 weeks');
                $endDate = (clone $startDate)->modify('+4 hours');

                $slot = new SellSlot();
                $slot->setStartDate($startDate);
                $slot->setEndDate($endDate);
                $slot->setState(EnumState::Active);
                $slot->setShop($shop);
                $slot->setCreatedAt(new \DateTime());
                $slot->setUpdatedAt(new \DateTime());

                $manager->persist($slot);
                $this->addReference(self::SLOT_REFERENCE . "$i-$j", $slot);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [ShopFixtures::class];
    }
}
