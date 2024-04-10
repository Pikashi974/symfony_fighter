<?php

namespace App\DataFixtures;

use App\Entity\Champion;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ChampionFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $champion = new Champion();
        $champion->setName("Hitori Goto")
            ->setPower(3000)
            ->setPv(3000);
        $manager->persist($champion);
        $champion2 = new Champion();
        $champion2->setName("Ryo")
            ->setPower(2500)
            ->setPv(2000);
        $manager->persist($champion2);

        $manager->flush();
    }
}
