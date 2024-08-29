<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker\Factory;

class UserFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $userPasswordHasher)
    {}

    public function load(ObjectManager $manager): void
    {
        // Crée un utilisateur administrateur
        $this->createAdminUser($manager);

        // Crée une instance de Faker pour générer des données de test en français
        $faker = Factory::create('fr_FR');

        // Création des utilisateurs avec des données aléatoires
        for ($i = 0; $i < 5; $i++) {
            $user = new User();
            $user
                ->setEmail('user' . $i . '@mail.com')
                ->setPassword($this->userPasswordHasher->hashPassword(
                    $user,
                    'password'
                ))
                ->setRoles(['ROLE_USER'])
                ->setCreatedAt(new \DateTimeImmutable())
                ->setUpdatedAt(new \DateTimeImmutable());

            $this->addReference('user_' . $i, $user);

            $manager->persist($user);
        }

        // Enregistre toutes les entités persistées dans la base de données
        $manager->flush();
    }

    public function createAdminUser(ObjectManager $manager): void
    {
        $user = new User();
        $user
            ->setEmail('admin@mail.com')
            ->setPassword($this->userPasswordHasher->hashPassword(
                $user,
                'password'
            ))
            ->setRoles(['ROLE_ADMIN'])
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable());

        $manager->persist($user);
        $this->addReference('admin_user', $user);
    }
}
