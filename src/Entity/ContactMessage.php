<?php

namespace App\Entity;

use App\Entity\Traits\UtilsCreatedUpdated;
use App\Repository\ContactMessageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContactMessageRepository::class)]
#[ORM\Table(name: 'contact_message')]
class ContactMessage
{

    use UtilsCreatedUpdated;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $subject = null;

    #[ORM\Column(type: 'text')]
    private ?string $message = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $attachmentFilename = null;

    #[ORM\ManyToOne(targetEntity: "App\Entity\User")]
    private ?\App\Entity\User $user = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    // getters & setters pour chaque propriété
    public function getId(): ?int { return $this->id; }
    public function getSubject(): ?string { return $this->subject; }
    public function setSubject(string $subject): self { $this->subject = $subject; return $this; }
    public function getMessage(): ?string { return $this->message; }
    public function setMessage(string $message): self { $this->message = $message; return $this; }
    public function getAttachmentFilename(): ?string { return $this->attachmentFilename; }
    public function setAttachmentFilename(?string $filename): self { $this->attachmentFilename = $filename; return $this; }
    public function getUser(): ?\App\Entity\User { return $this->user; }
    public function setUser(?\App\Entity\User $user): self { $this->user = $user; return $this; }
}