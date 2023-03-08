<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\FormerRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable;

#[ORM\Entity(repositoryClass: FormerRepository::class)]
#[ApiResource]

#[GetCollection(
    // security: ':("ROLE_ADMIN")'
)]

#[Get(
    security: 'object.user_id === user or :("ROLE_ADMIN")'
)]


#[Post(
    security: ':("IS_AUTHENTICATED_FULLY") or :("ROLE_ADMIN")'
)]


#[Delete(
    security: ':("ROLE_ADMIN")'
)]


#[Put(
    security: 'object.user_id === user or :("ROLE_ADMIN")'
)]


#[Patch(
    security: 'object.user_id === user or :("ROLE_ADMIN")'
)]



class Former
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'former')]
    #[ORM\JoinColumn(nullable: false)]
    public ?User $user_id = null;

    #[ORM\Column()]
    private ?bool $valid = false;

    #[ORM\Column(length: 255)]
    private ?string $accountOwner = null;

    #[ORM\Column(length: 255)]
    private ?string $accountIban = null;

    #[ORM\Column(length: 255)]
    private ?string $accountBankName = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private string|\DateTimeInterface $createdAt;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private string|null|\DateTimeInterface $updated_at;

    #[ORM\Column(nullable: true)]
    private ?bool $refused = null;

    public function __construct() {
        $this->createdAt = new \DateTime();
        $this->updated_at = new \DateTime();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(User $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function isValid(): ?bool
    {
        return $this->valid;
    }

    public function setValid(bool $valid): self
    {
        $this->valid = $valid;

        return $this;
    }

    public function getAccountOwner(): ?string
    {
        return $this->accountOwner;
    }

    public function setAccountOwner(string $accountOwner): self
    {
        $this->accountOwner = $accountOwner;

        return $this;
    }

    public function getAccountIban(): ?string
    {
        return $this->accountIban;
    }

    public function setAccountIban(string $accountIban): self
    {
        $this->accountIban = $accountIban;

        return $this;
    }

    public function getAccountBankName(): ?string
    {
        return $this->accountBankName;
    }

    public function setAccountBankName(string $accountBankName): self
    {
        $this->accountBankName = $accountBankName;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function isRefused(): ?bool
    {
        return $this->refused;
    }

    public function setRefused(?bool $refused): self
    {
        $this->refused = $refused;

        return $this;
    }
}
