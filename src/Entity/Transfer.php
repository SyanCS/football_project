<?php

namespace App\Entity;

use App\Repository\TransferRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TransferRepository::class)
 */
class Transfer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({ "transfer:read"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Player::class)
     * @ORM\JoinColumn(nullable=false)
     * @Groups({ "transfer:read"})
     * 
     */
    private $player;

    /**
     * @ORM\ManyToOne(targetEntity=Team::class)
     * @ORM\JoinColumn(nullable=false)
     * @Groups({ "transfer:read"})
     */
    private $fromTeam;

    /**
     * @ORM\ManyToOne(targetEntity=Team::class)
     * @ORM\JoinColumn(nullable=false)
     * @Groups({ "transfer:read"})
     */
    private $toTeam;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * @Groups({ "transfer:read"})
     */
    private $transferAmount;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({ "transfer:read"})
     */
    private $transferDate;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlayer(): ?Player
    {
        return $this->player;
    }

    public function setPlayer(?Player $player): self
    {
        $this->player = $player;

        return $this;
    }

    public function getFromTeam(): ?Team
    {
        return $this->fromTeam;
    }

    public function setFromTeam(?Team $fromTeam): self
    {
        $this->fromTeam = $fromTeam;

        return $this;
    }

    public function getToTeam(): ?Team
    {
        return $this->toTeam;
    }

    public function setToTeam(?Team $toTeam): self
    {
        $this->toTeam = $toTeam;

        return $this;
    }

    public function getTransferAmount(): ?string
    {
        return $this->transferAmount;
    }

    public function setTransferAmount(string $transferAmount): self
    {
        $this->transferAmount = $transferAmount;

        return $this;
    }

    public function getTransferDate(): ?\DateTimeInterface
    {
        return $this->transferDate;
    }

    public function setTransferDate(\DateTimeInterface $transferDate): self
    {
        $this->transferDate = $transferDate;

        return $this;
    }
}
