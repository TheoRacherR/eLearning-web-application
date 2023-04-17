<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
#[ApiResource]

#[GetCollection(
    security: 'is_granted("IS_AUTHENTICATED_FULLY")'
)]

#[Get(
    // security: 'is_granted("IS_AUTHENTICATED_FULLY") or is_granted("ROLE_ADMIN")'
)]

#[Post(
    security: 'is_granted("IS_AUTHENTICATED_FULLY") or is_granted("ROLE_ADMIN")'
)]

#[Delete(
    security: 'is_granted("IS_AUTHENTICATED_FULLY") or is_granted("ROLE_ADMIN")'
)]

#[Put(
    security: 'is_granted("IS_AUTHENTICATED_FULLY") or is_granted("ROLE_ADMIN")'
)]

#[Patch(
    security: 'is_granted("IS_AUTHENTICATED_FULLY") or is_granted("ROLE_ADMIN")'
)]


class Question
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'questions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Course $course = null;

    #[ORM\OneToMany(mappedBy: 'question', targetEntity: Answer::class, orphanRemoval: true)]
    private Collection $answers;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $settings = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $good_answer = null;

    public function __construct()
    {
        $this->answers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCourse(): ?Course
    {
        return $this->course;
    }

    public function setCourse(?Course $course): self
    {
        $this->course = $course;

        return $this;
    }

    /**
     * @return Collection<int, Answer>
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(Answer $answer): self
    {
        if (!$this->answers->contains($answer)) {
            $this->answers->add($answer);
            $answer->setQuestion($this);
        }

        return $this;
    }

    public function removeAnswer(Answer $answer): self
    {
        if ($this->answers->removeElement($answer)) {
            // set the owning side to null (unless already changed)
            if ($answer->getQuestion() === $this) {
                $answer->setQuestion(null);
            }
        }

        return $this;
    }

    public function getSettings(): ?string
    {
        return $this->settings;
    }

    public function setSettings(?string $settings): self
    {
        $this->settings = $settings;

        return $this;
    }

    public function getGoodAnswer(): ?string
    {
        return $this->good_answer;
    }

    public function setGoodAnswer(?string $good_answer): self
    {
        $this->good_answer = $good_answer;

        return $this;
    }
}
