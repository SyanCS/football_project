<?php

// src/Service/TransferService.php

namespace App\Service;

use App\Entity\Player;
use App\Entity\Team;
use App\Entity\Transfer;
use Doctrine\ORM\EntityManagerInterface;

class TransferService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function transferPlayer(Player $player, Team $fromTeam, Team $toTeam, float $transferAmount): Transfer
    {
        // Update player's team
        $player->setTeam($toTeam);

        // Update teams' money balances
        $fromTeam->setMoneyBalance($fromTeam->getMoneyBalance() + $transferAmount);
        $toTeam->setMoneyBalance($toTeam->getMoneyBalance() - $transferAmount);

        // Create a new transfer record
        $transfer = new Transfer();
        $transfer->setPlayer($player);
        $transfer->setFromTeam($fromTeam);
        $transfer->setToTeam($toTeam);
        $transfer->setTransferAmount($transferAmount);
        $transfer->setTransferDate(new \DateTime());

        // Persist the transfer record
        $this->entityManager->persist($transfer);
        $this->entityManager->flush();

        return $transfer;
    }
}
