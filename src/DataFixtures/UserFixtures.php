<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $user = new User();
        $user->setEmail("testAdmin@gmail.com")
            ->setPassword(password_hash("password", PASSWORD_DEFAULT))
            ->setUsername("testAdmin")
            ->setRoles(['ROLE_ADMIN']);
        $manager->persist($user);

        $user2 = new User();
        $user2->setEmail("test@gmail.com")
            ->setPassword(password_hash("password", PASSWORD_DEFAULT))
            ->setUsername("test")
            ->setRoles(['ROLE_USER']);
        $manager->persist($user2);

        $manager->flush();
    }
}
