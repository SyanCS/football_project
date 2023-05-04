<?php

namespace App\DataFixtures;

use App\Entity\Player;
use App\Entity\Team;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PlayerFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $teams = $manager->getRepository(Team::class)->findAll();

        foreach ($teams as $team) {
            $numPlayers = rand(5, 15);

            for ($i = 0; $i < $numPlayers; $i++) {
                $player = new Player();
                $player->setName('Player ' . $i);
                $player->setSurname('Surname ' . $i);
                $player->setTeam($team);

                $manager->persist($player);
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            TeamFixtures::class,
        ];
    }
}
