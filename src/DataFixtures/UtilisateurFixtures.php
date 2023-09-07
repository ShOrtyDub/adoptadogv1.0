<?php

namespace App\DataFixtures;

use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UtilisateurFixtures extends Fixture
{
    /**
     * @var Generator
     */
    private Generator $faker;
    /**
     * @var UserPasswordHasherInterface
     */
    private UserPasswordHasherInterface $passwordHasher;

    /**
     * Constructeur.
     * @param UserPasswordHasherInterface $passwordHasher
     */
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
        $this->faker = Factory::create('fr_FR');
    }

    /**
     * Peuple la table Utilisateur dans la base de données.
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 5; $i++) {
            $utilisateur[$i] = new Utilisateur();
            $utilisateur[$i]->setEmail($this->faker->email);
            $utilisateur[$i]->setNom($this->faker->lastName());
            $utilisateur[$i]->setPrenom($this->faker->firstName());
            $utilisateur[$i]->setIsValide($this->faker->boolean());
            $utilisateur[$i]->setAge($this->faker->numberBetween(18, 50));
            $utilisateur[$i]->setTelephone("05" . mt_rand(00000000, 99999999));
            $utilisateur[$i]->setPhoto('https://loremflickr.com/640/480/face?rand=' . '{' . rand(1, 999) . '}');
            $hashedPassword = $this->passwordHasher->hashPassword($utilisateur[$i], '123');
            $utilisateur[$i]->setPassword($hashedPassword);
            $utilisateur[$i]->setRoles(['ROLE_VISITOR']);
            $manager->persist($utilisateur[$i]);
        }

        $manager->flush();
    }
}
