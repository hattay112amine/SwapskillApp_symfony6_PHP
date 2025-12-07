<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Regular user
        $user = new User();
        $user->setEmail('test@example.com');
        $user->setName('John Doe');
        $user->setPhone('12345678');

        // Hash password
        $user->setPassword(
            $this->passwordHasher->hashPassword($user, 'password123')
        );

        $manager->persist($user);
        $this->addReference('user_test', $user);
        // Admin user
        $admin = new User();
        $admin->setEmail('admin@example.com');
        $admin->setName('Jane Smith');
        $admin->setPhone('87654321');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword(
            $this->passwordHasher->hashPassword($admin, 'adminpassword')
        );
        $manager->persist($admin);
        $this->addReference('admin_test', $admin);

        $manager->flush();
    }
}

