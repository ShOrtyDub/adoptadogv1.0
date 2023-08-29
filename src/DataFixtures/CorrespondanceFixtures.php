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

    protected function getRandomReference(string $className, object $manager)
    {
        $list = $manager->getRepository($className)->findAll();
        return $list[array_rand($list)];
    }

    public function getDependencies(): array
    {
        return [
            UtilisateurFixtures::class,
            ChienFixtures::class
        ];
    }
}
