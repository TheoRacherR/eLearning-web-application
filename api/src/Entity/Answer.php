<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\AnswerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnswerRepository::class)]
#[ApiResource]

#[GetCollection(
    security: 'is_granted("ROLE_ADMIN")'
)]

#[Get(
    security: 'is_granted("ROLE_ADMIN") or object.question.course.user_id === user or is_granted("ROLE_FORMER")'

)]

#[Post(
    security: 'is_granted("ROLE_ADMIN") or is_granted("ROLE_FORMER")'
)]

#[Put(
    security: 'is_granted("ROLE_ADMIN")'
)]

#[Patch(
    security: 'is_granted("ROLE_ADMIN")'
)]

#[Delete(
    security: 'is_granted("ROLE_ADMIN")'
)]

class Answer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'answers')]
    #[ORM\JoinColumn(nullable: false)]
    public ?Question $question = null;

    #[ORM\ManyToOne(inversedBy: 'answers')]
    private ?User $account = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $value = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getAccount(): ?User
    {
        return $this->account;
    }

    public function setAccount(?User $account): self
    {
        $this->account = $account;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): self
    {
        $this->value = $value;

        return $this;
    }
}
