<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use App\Entity\Chien;
use App\Entity\Commentaire;
use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CommentaireFixtures extends Fixture implements DependentFixtureInterface
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
     * Peuple la table Commentaire dans la base de données.
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 5; $i++) {
            $commentaire[$i] = new Commentaire();
            $commentaire[$i]->setTexte($this->faker->text(50));
            $commentaire[$i]->setIsValide($this->faker->boolean());
            $commentaire[$i]->setDateCreation($this->faker->dateTime());
            $random = $this->faker->randomElement([0, 1]);
            if ($random === 0) {
                $commentaire[$i]->setFkIdAdmin($this->getRandomReference(Admin::class, $manager));
            } else {
                $commentaire[$i]->setFkIdUtilisateur($this->getRandomReference(Utilisateur::class, $manager));
            }
            $commentaire[$i]->setFkIdChien($this->getRandomReference(Chien::class, $manager));
            $manager->persist($commentaire[$i]);
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

    /**
     * Détermine de quelle autre table dépend la table commentaire pour gérer l'ordre du chargement des fixtures.
     * @return string[]
     */
    public function getDependencies(): array
    {
        return [UtilisateurFixtures::class];
    }
}
