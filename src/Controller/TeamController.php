<?php

namespace App\Controller;

use App\Entity\Team;
use App\Entity\Player;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class TeamController extends AbstractController
{
    private $teamRepository;
    private $entityManager;
    private $serializer;

    public function __construct(TeamRepository $teamRepository, EntityManagerInterface $entityManager, SerializerInterface $serializer)
    {
        $this->teamRepository = $teamRepository;
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/teams", name="teams", methods={"GET"})
     */
    public function getAllTeams(Request $request, SerializerInterface $serializer): JsonResponse
    {
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 10);

        $paginator = $this->teamRepository->getPaginatedTeams($page, $limit);
        $totalItems = $paginator->count();
        $totalPages = ceil($totalItems / $limit);

        return $this->json([
            'teams' => $paginator->getIterator(),
            'total' => $totalItems,
            'page' => $page,
            'total_pages' => $totalPages,
        ], JsonResponse::HTTP_OK, [], ['groups' => ['team:read', 'player:read']]);
    }



    /**
     * @Route("/teams", name="create_team", methods={"POST"})
     */
    public function createTeam(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $team = new Team();
        $team->setName($data['name']);
        $team->setCountry($data['country']);
        $team->setMoneyBalance($data['moneyBalance']);

        foreach ($data['players'] as $playerData) {
            $player = new Player();
            $player->setName($playerData['name']);
            $player->setSurname($playerData['surname']);
            $player->setTeam($team);
            $team->addPlayer($player);
        }

        $this->entityManager->persist($team);
        $this->entityManager->flush();

        $response_data = $this->serializer->serialize($team, 'json', ['groups' => 'team:read']);

        return new JsonResponse($response_data, JsonResponse::HTTP_CREATED, [], true);
    }
}
