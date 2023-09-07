<?php

namespace App\DataFixtures;

use App\Entity\Chien;
use App\Entity\Correspondance;
use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CorrespondanceFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * Peuple la table Correspondance dans la base de données.
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 5; $i++) {
            $correspondance[$i] = new Correspondance();
            $correspondance[$i]->setFkIdChien($this->getRandomReference(Chien::class, $manager));
            $correspondance[$i]->setFkIdUtilisateur($this->getRandomReference(Utilisateur::class, $manager));

            $manager->persist($correspondance[$i]);
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
     * Détermine de quelles autres tables dépend la table correspondance pour gérer l'ordre du chargement des fixtures.
     * @return string[]
     */
    public function getDependencies(): array
    {
        return [
            UtilisateurFixtures::class,
            ChienFixtures::class
        ];
    }
}
