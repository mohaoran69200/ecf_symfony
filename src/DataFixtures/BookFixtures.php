<?php

namespace App\DataFixtures;

use App\Entity\Book;
use App\Entity\Author;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use DateTimeImmutable;

class BookFixtures extends Fixture implements DependentFixtureInterface
{
    private array $bookData = [
        'author_0' => ['Book Title 1', 'Book Title 2'],
        'author_1' => ['Book Title 3', 'Book Title 4'],
        'author_2' => ['Book Title 5', 'Book Title 6'],
        'author_3' => ['Book Title 7', 'Book Title 8'],
        'author_4' => ['Book Title 9', 'Book Title 10'],
        'author_5' => ['Book Title 11', 'Book Title 12'],
        'author_6' => ['Book Title 13', 'Book Title 14'],
        'author_7' => ['Book Title 15', 'Book Title 16'],
        'author_8' => ['Book Title 17', 'Book Title 18'],
        'author_9' => ['Book Title 19', 'Book Title 20'],
    ];

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // Assurez-vous que les références des auteurs sont correctement récupérées
        $authors = [];
        foreach (array_keys($this->bookData) as $authorRef) {
            $authors[$authorRef] = $this->getReference($authorRef);
        }

        // Récupère les utilisateurs référencés
        $users = [];
        for ($i = 0; $i < 5; $i++) {
            $users[$i] = $this->getReference('user_' . $i);
        }

        // Crée des livres en utilisant les auteurs et utilisateurs référencés
        for ($i = 0; $i < 15; $i++) {
            $authorRef = $faker->randomElement(array_keys($this->bookData));
            $bookTitle = $faker->randomElement($this->bookData[$authorRef]);
            $userRef = $faker->randomElement(array_keys($users));

            $book = new Book();
            $book
                ->setTitle($bookTitle)
                ->setDescription($faker->text)
                ->setDatePublication($faker->dateTimeThisDecade)
                ->setAuthor($authors[$authorRef])
                ->setUser($users[$userRef])
                ->setCreatedAt(new DateTimeImmutable())
                ->setUpdatedAt(new DateTimeImmutable());

            $manager->persist($book);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [UserFixtures::class, AuthorFixtures::class];
    }
}
