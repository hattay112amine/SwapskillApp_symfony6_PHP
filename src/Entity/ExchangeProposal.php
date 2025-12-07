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
    private ?string $status = 'pending';

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $requester = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $receiver = null;


    public function getReceiver(): ?User
    {
        return $this->receiver;
    }

    public function setReceiver(User $receiver): static
    {
        $this->receiver = $receiver;
        return $this;
    }




    public function getId(): ?int
    {
        return $this->id;
    }
    public function getRequester(): ?User
    {
        return $this->requester;
    }

    public function setRequester(User $requester): static
    {
        $this->requester = $requester;
        return $this;
    }
    public function getOfferedSkill(): ?Skill
    {
        return $this->offeredSkill;
    }

    public function setOfferedSkill(Skill $offeredSkill): static
    {
        $this->offeredSkill = $offeredSkill;
        return $this;
    }

    public function getRequestedSkill(): ?Skill
    {
        return $this->requestedSkill;
    }

    public function setRequestedSkill(Skill $requestedSkill): static
    {
        $this->requestedSkill = $requestedSkill;
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
