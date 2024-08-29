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

        // Crée des auteurs avec des données aléatoires
        for ($i = 0; $i < 10; $i++) {
            $author = new Author();
            $author
                ->setFirstName($faker->firstName)
                ->setLastName($faker->lastName)
                ->setBirthDate($faker->dateTimeThisCentury)
                ->setCreatedAt(new DateTimeImmutable())
                ->setUpdatedAt(new DateTimeImmutable());

            $manager->persist($author);
            $this->addReference('author_' . $i, $author);
        }

        $manager->flush();
    }
}
