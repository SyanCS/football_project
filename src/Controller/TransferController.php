<?php

namespace App\Controller;

use App\Entity\Player;
use App\Repository\TransferRepository;
use App\Repository\PlayerRepository;
use App\Repository\TeamRepository;
use App\Service\TransferService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class TransferController extends AbstractController
{
    private $transferRepository;
    private $playerRepository;
    private $teamRepository;
    private $transferService;
    private $entityManager;
    private $serializer;

    public function __construct(TransferRepository $transferRepository, PlayerRepository $playerRepository, TeamRepository $teamRepository, TransferService $transferService, EntityManagerInterface $entityManager, SerializerInterface $serializer)
    {
        $this->transferRepository = $transferRepository;
        $this->playerRepository = $playerRepository;
        $this->teamRepository = $teamRepository;
        $this->transferService = $transferService;
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/transfers", name="create_transfer", methods={"POST"})
     */
    public function createTransfer(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $player = $this->playerRepository->find($data['playerId']);
        $fromTeam = $this->teamRepository->find($data['sellerId']);
        $toTeam = $this->teamRepository->find($data['buyerId']);
        $transferAmount = $data['transferAmount'];

        if (!$player || !$fromTeam || !$toTeam) {
            return new JsonResponse(['error' => 'Invalid player or team'], JsonResponse::HTTP_BAD_REQUEST);
        }

        if ($toTeam->getMoneyBalance() < $transferAmount) {
            return new JsonResponse(['error' => 'Insufficient funds for the buyer team'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $transfer = $this->transferService->transferPlayer($player, $fromTeam, $toTeam, $transferAmount);

        $this->entityManager->persist($transfer);
        $this->entityManager->flush();

        $response_data = $this->serializer->serialize($transfer, 'json', ['groups' => 'transfer:read']);

        return new JsonResponse($response_data, JsonResponse::HTTP_CREATED, [], true);
    }

    /**
     * @Route("/transfers", name="get_transfers", methods={"GET"})
     */
    public function getTransfers(): JsonResponse
    {
        $transfers = $this->transferRepository->findAll();
        $data = $this->serializer->serialize([
            'transfers' => $transfers,
        ], 'json', ['groups' => ['transfer:read']]);

        return new JsonResponse($data, JsonResponse::HTTP_OK, [], true);
    }
}
