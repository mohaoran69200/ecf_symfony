<?php

namespace App\DataFixtures;

use App\Entity\Author;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use DateTimeImmutable;

class AuthorFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // Assurez-vous que les références des utilisateurs sont disponibles
        $users = [];
        for ($i = 0; $i < 5; $i++) {
            $users[] = $this->getReference('user_' . $i);
        }

        // Crée des auteurs avec des données aléatoires
        for ($i = 0; $i < 10; $i++) {
            $author = new Author();
            $author
                ->setFirstName($faker->firstName)
                ->setLastName($faker->lastName)
                ->setBirthDate($faker->dateTimeThisCentury)
                ->setCreatedAt(new DateTimeImmutable())
                ->setUpdatedAt(new DateTimeImmutable())
                ->setUser($users[array_rand($users)]);  // Associe l'auteur à un utilisateur aléatoire

            $manager->persist($author);
            $this->addReference('author_' . $i, $author);
        }

        $manager->flush();
    }
}
