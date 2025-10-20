<?php

namespace App\Entity;

use App\Repository\EventRegistrationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventRegistrationRepository::class)]
#[ORM\Table(name: "event_registration")] // nom explicite de la table
class EventRegistration
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    // Relation ManyToOne vers User (participant)
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $participant = null;

    // Relation ManyToOne vers Event
    #[ORM\ManyToOne(targetEntity: Event::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Event $event = null;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $registeredAt;

    #[ORM\Column(length: 20)]
    private string $status; // "confirmed", "pending", "cancelled"

    public function __construct()
    {
        $this->registeredAt = new \DateTime();
        $this->status = 'pending'; // valeur par défaut
    }

    // --- Getters et setters ---
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getParticipant(): ?User
    {
        return $this->participant;
    }

    public function setParticipant(User $participant): self
    {
        $this->participant = $participant;
        return $this;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(Event $event): self
    {
        $this->event = $event;
        return $this;
    }

    public function getRegisteredAt(): \DateTimeInterface
    {
        return $this->registeredAt;
    }

    public function setRegisteredAt(\DateTimeInterface $registeredAt): self
    {
        $this->registeredAt = $registeredAt;
        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }
}
