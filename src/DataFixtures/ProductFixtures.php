<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\SellSlot;
use App\Entity\User;
use App\Enum\EnumLabel;
use App\Enum\EnumState;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{
    private const PRODUCTS_PER_PRODUCER = 8;
    private const UNITS = ['kg', 'pièce', 'botte', 'litre', 'barquette 500g'];
    private const PRODUCT_NAMES = [
        'Tomates anciennes', 'Courgettes', 'Miel de lavande', 'Œufs fermiers',
        'Fromage de chèvre', 'Pommes Gala', 'Salade batavia', 'Huile d\'olive',
        'Confiture d\'abricot', 'Pain au levain', 'Fraises', 'Poireaux',
    ];

    private Generator $faker;

    public function __construct(
        private readonly SluggerInterface $slugger
    ) {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        $certifications = EnumLabel::cases();

        for ($i = 0; $i < UserFixtures::PRODUCER_COUNT; $i++) {
            $producer = $this->getReference(UserFixtures::PRODUCER_REFERENCE . $i, User::class);

            for ($j = 0; $j < self::PRODUCTS_PER_PRODUCER; $j++) {
                $title = $this->faker->randomElement(self::PRODUCT_NAMES);

                $product = new Product();
                $product->setTitle($title);
                $product->setSlug($this->slugger->slug($title)->lower()->toString());
                $product->setDescription($this->faker->sentence(12));
                $product->setUnit($this->faker->randomElement(self::UNITS));
                $product->setPrice($this->faker->randomFloat(2, 1, 40));
                $product->setState(EnumState::Active);
                $product->setImageFileName('tomates.jpg');
                $product->setProducer($producer);
                $product->setCreatedAt(new \DateTime());
                $product->setUpdatedAt(new \DateTime());
                $product->setCertifications(
                    $this->faker->randomElements($certifications, $this->faker->numberBetween(0, 2))
                );

                // ManyToMany : on rattache le produit à 1 à 3 créneaux du shop du producteur
                $slotIndexes = $this->faker->randomElements(
                    range(0, SellSlotFixtures::SLOTS_PER_SHOP - 1),
                    $this->faker->numberBetween(1, 3)
                );

                foreach ($slotIndexes as $slotIndex) {
                    $product->addSellSlot(
                        $this->getReference(SellSlotFixtures::SLOT_REFERENCE . "$i-$slotIndex", SellSlot::class)
                    );
                }

                $manager->persist($product);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            SellSlotFixtures::class,
        ];
    }
}
