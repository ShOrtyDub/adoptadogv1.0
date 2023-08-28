<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UtilisateurFixtures extends Fixture
{
    private $faker;
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 5; $i++) {
            $utilisateur[$i] = new Utilisateur();
            $utilisateur[$i]->setEmail($this->faker->email);
            $utilisateur[$i]->setNom($this->faker->lastName());
            $utilisateur[$i]->setPrenom($this->faker->firstName());
            $utilisateur[$i]->setIsValide($this->faker->boolean());
            $utilisateur[$i]->setAge($this->faker->numberBetween(18, 50));
            $hashedPassword = $this->passwordHasher->hashPassword($utilisateur[$i], '123');
            $utilisateur[$i]->setPassword($hashedPassword);
            $utilisateur[$i]->setRoles(['ROLE_VISITOR']);
            $manager->persist($utilisateur[$i]);
        }

        $manager->flush();
    }
}
