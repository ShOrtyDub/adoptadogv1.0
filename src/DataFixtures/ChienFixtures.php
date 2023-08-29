<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use App\Entity\Chien;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ChienFixtures extends Fixture
{
    private $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 5; $i++) {
            $chien[$i] = new Chien();
            $gender = $this->faker->randomElement([1, 2]);
            if ($gender === 1) {
                $chien[$i]->setNom($this->faker->firstNameMale);
            } else {
                $chien[$i]->setNom($this->faker->firstNameFemale);
            }
            $chien[$i]->setAge($this->faker->numberBetween(1, 15));
            $chien[$i]->setPhoto('https://loremflickr.com/640/480/dog');
            $chien[$i]->setRace($this->faker->randomElement([
                'Labrador', 'Berger Allemand', 'Golden Retriever',
                'French Bulldog', 'Bulldog'
            ]));
            $chien[$i]->setCouleur($this->faker->randomElement(['Blanc', 'Noir', 'Beige', 'Marron', 'Gris']));
            $chien[$i]->setTaille($this->faker->numberBetween(20, 70));
            $chien[$i]->setPoids($this->faker->numberBetween(5, 35));
            $chien[$i]->setCaractere($this->faker->randomElement(['Calme', 'Joueur', 'Excité']));
            $chien[$i]->setFkIdAdmin($this->getRandomReference(Admin::class, $manager));
            $manager->persist($chien[$i]);
        }

        $manager->flush();
    }

    protected function getRandomReference(string $className, object $manager)
    {
        $list = $manager->getRepository($className)->findAll();
        return $list[array_rand($list)];
    }
}
