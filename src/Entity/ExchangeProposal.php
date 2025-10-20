<?php

namespace App\Entity;

use App\Repository\ExchangeProposalRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExchangeProposalRepository::class)]
//#[ORM\Table(name: "exchange_proposal")]
class ExchangeProposal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Relation ManyToOne vers Skill (le skill offert)
    #[ORM\ManyToOne(targetEntity: Skill::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Skill $offeredSkill = null;

    // Relation ManyToOne vers Skill (le skill demandé)
    #[ORM\ManyToOne(targetEntity: Skill::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Skill $requestedSkill = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $proposal = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $status = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getProposal(): ?string
    {
        return $this->proposal;
    }

    public function setProposal(?string $proposal): static
    {
        $this->proposal = $proposal;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTime $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
