<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use App\Entity\Chien;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ChienFixtures extends Fixture
{
    private $faker;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
//        $this->truncate($manager);

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
                'Labrador Retriever', 'Berger Allemand', 'Golden Retriever',
                'French Bulldog', 'Bulldog', 'Beagle', 'Rottweiler',
                'Yorkshire', 'Boxer', 'Border Collie'
            ]));
            $chien[$i]->setCouleur($this->faker->randomElement(['Blanc', 'Noir', 'Beige', 'Marron', 'Gris']));
            $chien[$i]->setTaille($this->faker->numberBetween(20, 70));
            $chien[$i]->setPoids($this->faker->numberBetween(5, 35));
            $chien[$i]->setCaractere($this->faker->randomElement(['Calme', 'Joueur', 'Excité']));
            dump($this->getReferencedObject(Admin::class, 1, $manager));
            $chien[$i]->setFkIdAdmin($this->getReferencedObject(Admin::class, 1, $manager));
            $manager->persist($chien[$i]);
        }

        $manager->flush();
    }

    protected function getReferencedObject(string $className, int $id, object $manager)
    {
        return $manager->find($className, $id);
    }

//    protected function truncate($manager): void
//    {
//        /**
//         * @var Connection db
//         */
//        $db = $manager->getConnection();
//        $db->beginTransaction();
//
//        $sql = '
//        SET FOREIGN_KEY_CHECKS = 0;
//        TRUNCATE admin;
//        TRUNCATE chien;
//        SET FOREIGN_KEY_CHECKS =1;';
//
//        $db->prepare($sql);
//        $db->executeQuery($sql);
//        $db->commit();
//        $db->beginTransaction();
//    }
}
