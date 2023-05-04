<?php

namespace App\Tests\Controller;

use App\Entity\Team;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TeamControllerTest extends WebTestCase
{

    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testCreateTeam(): void
    {
        
        $teamData = [
            'name' => 'Test Team',
            'country' => 'Test Country',
            'moneyBalance' => 1000000,
            'players' => [
                [
                    'name' => 'John',
                    'surname' => 'Doe',
                ],
                [
                    'name' => 'Jane',
                    'surname' => 'Doe',
                ],
            ],
        ];

        $this->client->request(
            'POST',
            '/api/v1/teams',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($teamData)
        );

        $response = $this->client->getResponse();
        $responseData = json_decode($response->getContent(), true);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertArrayHasKey('id', $responseData);
        $this->assertEquals($teamData['name'], $responseData['name']);
        $this->assertEquals($teamData['country'], $responseData['country']);
        $this->assertEquals($teamData['moneyBalance'], $responseData['moneyBalance']);
    }

    public function testGetAllTeams(): void
    {
        $this->client->request('GET', '/api/v1/teams');

        $response = $this->client->getResponse();
        $responseData = json_decode($response->getContent(), true);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertArrayHasKey('teams', $responseData);
        $this->assertArrayHasKey('total', $responseData);
        $this->assertArrayHasKey('page', $responseData);
        $this->assertArrayHasKey('total_pages', $responseData);
    }
}
