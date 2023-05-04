<?php

namespace App\DataFixtures;

use App\Entity\Team;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TeamFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 5; $i++) {
            $team = new Team();
            $team->setName('Team ' . $i);
            $team->setCountry('Country ' . $i);
            $team->setMoneyBalance(mt_rand(100000, 1000000));
            
            $manager->persist($team);
        }

        $manager->flush();
    }
}
