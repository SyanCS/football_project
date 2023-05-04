<?php 

namespace App\Tests\Controller;

use App\Entity\Team;
use App\Entity\Player;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TransferControllerTest extends WebTestCase
{
    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testCreateTransfer(): void
    {
        $entityManager = $this->client->getContainer()->get('doctrine')->getManager();

        // Create test teams and player
        $team1 = new Team();
        $team1->setName('Test Team 1');
        $team1->setCountry('Country 1');
        $team1->setMoneyBalance(1000000);

        $team2 = new Team();
        $team2->setName('Test Team 2');
        $team2->setCountry('Country 2');
        $team2->setMoneyBalance(1000000);

        $player = new Player();
        $player->setName('Test Player');
        $player->setSurname('Surname');
        $player->setTeam($team1);

        $entityManager->persist($team1);
        $entityManager->persist($team2);
        $entityManager->persist($player);
        $entityManager->flush();

        $this->client->request('POST', '/api/v1/transfers', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'playerId' => $player->getId(),
            'sellerId' => $team1->getId(),
            'buyerId' => $team2->getId(),
            'transferAmount' => 500000
        ]));

        $this->assertEquals(Response::HTTP_CREATED, $this->client->getResponse()->getStatusCode());
    }
}
