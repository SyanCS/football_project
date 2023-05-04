<?php

namespace App\Controller;

use App\Entity\Player;
use App\Entity\Team;
use App\Entity\Transfer;
use App\Repository\PlayerRepository;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class PlayerController extends AbstractController
{
    private $playerRepository;
    private $teamRepository;
    private $entityManager;
    private $serializer;

    public function __construct(PlayerRepository $playerRepository, TeamRepository $teamRepository, EntityManagerInterface $entityManager, SerializerInterface $serializer)
    {
        $this->playerRepository = $playerRepository;
        $this->teamRepository = $teamRepository;
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
    }


    /**
     * @Route("/teams/{id}/players", name="get_team_players", methods={"GET"})
     */
    public function getTeamPlayers(int $id, PlayerRepository $playerRepository, TeamRepository $teamRepository): JsonResponse
    {
        $team = $teamRepository->find($id);

        if (!$team) {
            return new JsonResponse(null, JsonResponse::HTTP_NOT_FOUND);
        }

        $players = $team->getPlayers();

        $data = $this->serializer->serialize([
            'team' => $team,
            'players' => $players,
        ], 'json', ['groups' => ['team:read', 'player:read']]);

        return new JsonResponse($data, JsonResponse::HTTP_OK, [], true);
    }

    /**
     * @Route("/player", name="create_player", methods={"POST"})
     */
    public function createPlayer(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $player = new Player();
        $player->setName($data['name']);
        $player->setSurname($data['surname']);

        $this->entityManager->persist($player);
        $this->entityManager->flush();

        $response_data = $this->serializer->serialize($player, 'json', ['groups' => 'player:read']);

        return new JsonResponse($response_data, JsonResponse::HTTP_CREATED, [], true);
    }

    /**
     * @Route("/player/{id}", name="update_player", methods={"PUT"})
     */
    public function updatePlayer(Request $request, int $id): JsonResponse
    {
        $player = $this->playerRepository->find($id);

        if (!$player) {
            throw $this->createNotFoundException('Player with id ' . $id . ' does not exist');
        }

        $data = json_decode($request->getContent(), true);

        $player->setName($data['name'] ?? $player->getName());
        $player->setSurname($data['surname'] ?? $player->getSurname());

        $this->entityManager->flush();

        $response_data = $this->serializer->serialize($player, 'json', ['groups' => 'player:read']);

        return new JsonResponse($response_data, JsonResponse::HTTP_OK, [], true);
    }

    /**
     * @Route("/player/{id}", name="delete_player", methods={"DELETE"})
     */
    public function deletePlayer(int $id): JsonResponse
    {
        $player = $this->playerRepository->find($id);

        if (!$player) {
            throw $this->createNotFoundException('Player with id ' . $id . ' does not exist');
        }

        $this->entityManager->remove($player);
        $this->entityManager->flush();

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/player/transfer", name="transfer_player", methods={"POST"})
     */
    public function transferPlayer(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $player = $this->playerRepository->find($data['playerId']);
        $fromTeam = $this->teamRepository->find($data['fromTeamId']);
        $toTeam = $this->teamRepository->find($data['toTeamId']);
        $transferAmount = $data['transferAmount'];
        $transferDate = new \DateTime();

        // Perform necessary validations, e.g., player belongs to the fromTeam, enough balance, etc.

        // Update the player's team
        $player->setTeam($toTeam);
        $toTeam->addPlayer($player);
        $fromTeam->removePlayer($player);

        // Update the team's money balance
        $fromTeam->setMoneyBalance($fromTeam->getMoneyBalance() + $transferAmount);
        $toTeam->setMoneyBalance($toTeam->getMoneyBalance() - $transferAmount);

        // Save the transfer record
        $transfer = new Transfer();
        $transfer->setPlayer($player);
        $transfer->setFromTeam($fromTeam);
        $transfer->setToTeam($toTeam);
        $transfer->setTransferAmount($transferAmount);
        $transfer->setTransferDate($transferDate);
        $this->entityManager->persist($transfer);

        // Persist the changes
        $this->entityManager->flush();

        $response_data = $this->serializer->serialize($transfer, 'json', ['groups' => 'transfer:read']);

        return new JsonResponse($response_data, JsonResponse::HTTP_CREATED, [], true);
    }
}
