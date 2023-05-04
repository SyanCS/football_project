amespace App\Tests\Controller;
<?php

use App\Entity\Player;
use App\Entity\Team;
use App\Entity\Transfer;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class PlayerControllerTest extends WebTestCase
{
    public function testGetTeamPlayers(): void
    {
        $client = static::createClient();
        $teamId = 1;

        $client->request('GET', '/api/v1/teams/' . $teamId . '/players');
        $response = $client->getResponse();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('team', $data);
        $this->assertArrayHasKey('players', $data);
    }

    public function testTransferPlayer(): void
    {
        $client = static::createClient();

        // Create teams
        $fromTeam = new Team();
        $fromTeam->setName('From Team');
        $fromTeam->setCountry('Test Country');
        $fromTeam->setMoneyBalance(1000000);

        $toTeam = new Team();
        $toTeam->setName('To Team');
        $toTeam->setCountry('Test Country');
        $toTeam->setMoneyBalance(1000000);

        // Create player
        $player = new Player();
        $player->setName('Test Player');
        $player->setSurname('Test Surname');
        $player->setTeam($fromTeam);

        // Persist entities
        $em = $client->getContainer()->get('doctrine')->getManager();
        $em->persist($fromTeam);
        $em->persist($toTeam);
        $em->persist($player);
        $em->flush();

        // Make a transfer
        $data = [
            'playerId' => $player->getId(),
            'fromTeamId' => $fromTeam->getId(),
            'toTeamId' => $toTeam->getId(),
            'transferAmount' => 500000,
        ];

        $client->request('POST', '/api/v1/player/transfer', [], [], [], json_encode($data));
        $response = $client->getResponse();

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $transfer = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('id', $transfer);
        $this->assertArrayHasKey('player', $transfer);
        $this->assertArrayHasKey('fromTeam', $transfer);
        $this->assertArrayHasKey('toTeam', $transfer);
        $this->assertArrayHasKey('transferAmount', $transfer);
        $this->assertArrayHasKey('transferDate', $transfer);
    }
}
