<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use App\Entity\Chien;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ChienFixtures extends Fixture
{
    /**
     * @var \Faker\Generator
     */
    private $faker;

    /**
     * Constructeur.
     */
    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    /**
     * Peuple la table chien dans la base de données.
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 5; $i++) {
            $chien[$i] = new Chien();
            $gender = $this->faker->randomElement([1, 2]);
            if ($gender === 1) {
                $chien[$i]->setNom($this->faker->firstNameMale);
                $chien[$i]->setSexe('Mâle');
            } else {
                $chien[$i]->setNom($this->faker->firstNameFemale);
                $chien[$i]->setSexe('Femelle');
            }
            $numWords = $this->faker->numberBetween(10, 50);
            $randomText = $this->faker->paragraph($numWords, true);
            $chien[$i]->setDescription($randomText);
            $chien[$i]->setAge($this->faker->numberBetween(1, 15));
            $chien[$i]->setPhoto('https://loremflickr.com/640/480/dog?rand=' . '{' . rand(1, 999) . '}');
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

    /**
     * Obtient une référence aléatoire de la table spécifiée.
     * @param string $className
     * @param object $manager
     * @return mixed
     */
    protected function getRandomReference(string $className, object $manager)
    {
        $list = $manager->getRepository($className)->findAll();
        return $list[array_rand($list)];
    }
}
