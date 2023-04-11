<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Controller\DeleteUserController;
use App\Controller\RequestPasswordController;
use App\Controller\UpdateUserController;
use App\Controller\ValidateAccountController;
use App\Repository\UserRepository;
use App\State\UserAccountCreate;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Phalcon\Forms\Form;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
)]
#[GetCollection(
    // security: 'is_granted("ROLE_ADMIN")'
)]
#[Post(
    processor: UserAccountCreate::class
)]
#[Get(
    security: '(is_granted("IS_AUTHENTICATED_FULLY") and object === user) or (is_granted("ROLE_ADMIN"))'
)]
#[Get(
    uriTemplate: '/user/validate',
    defaults: ['identifiedBy' => 'token'],
    controller: ValidateAccountController::class,
    openapiContext: ['parameters' => [
        ['name' => 'token', 'in' => 'query', 'required' => true]
    ]],
    read: false,
    name: 'validate'
)]
#[Get(
    uriTemplate: '/user/request_password',
    defaults: ['identifiedBy' => 'mail'],
    controller: RequestPasswordController::class,
    openapiContext: ['parameters' => [
        ['name' => 'mail', 'in' => 'query', 'required' => true]
    ]],
    read: false,
    name: 'request_password'
)]
#[Patch(
    controller: UpdateUserController::class,
    security: 'is_granted("ROLE_ADMIN") or object === user'
)]
#[Delete(
    security: 'is_granted("ROLE_ADMIN")'
)]

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity('mail', 'Cette adresse mail est déjà utilisée')]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[Groups(['read'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['read'])]
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[Groups(['write'])]
    #[ORM\Column]
    private ?string $password = null;

    #[Assert\Email(message: 'L\'adresse mail {{ value }} n\'est pas valide',)]
    #[Groups(['read', 'write'])]
    #[ORM\Column(length: 255)]
    private ?string $mail = null;

    #[Groups(['read', 'write'])]
    #[ORM\Column(length: 255)]
    private ?string $firstname = null;

    #[Groups(['read', 'write'])]
    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    #[Groups(['read'])]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private string|\DateTimeInterface $createdAt;

    #[Groups(['read'])]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private string|\DateTimeInterface $updatedAt;

    #[Groups(['read'])]
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $lastActivity;

    #[Groups(['read'])]
    #[ORM\Column]
    private ?bool $valid = false;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $token = null;

    #[Groups(['read'])]
    #[ORM\OneToMany(mappedBy: 'account', targetEntity: UserCourse::class, orphanRemoval: true)]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    private Collection $userCourses;

    #[ORM\OneToMany(mappedBy: 'user_id', targetEntity: Comment::class, orphanRemoval: true)]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    private Collection $comments;

    #[ORM\OneToMany(mappedBy: 'user_id', targetEntity: Course::class, orphanRemoval: true)]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    private Collection $courses;

    #[Groups(['read'])]
    #[ORM\Column]
    private ?bool $ban = false;

    public function __construct()
    {
        $this->courses = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->userCourses = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->lastActivity = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->mail;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

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
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getLastActivity(): ?\DateTimeInterface
    {
        return $this->lastActivity;
    }

    public function setLastActivity(?\DateTimeInterface $lastActivity): self
    {
        $this->lastActivity = $lastActivity;

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

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @return Collection<int, UserCourse>
     */
    public function getUserCourses(): Collection
    {
        return $this->userCourses;
    }

    public function addUserCourse(UserCourse $userCourse): self
    {
        if (!$this->userCourses->contains($userCourse)) {
            $this->userCourses->add($userCourse);
            $userCourse->setAccount($this);
        }

        return $this;
    }

    public function removeUserCourse(UserCourse $userCourse): self
    {
        if ($this->userCourses->removeElement($userCourse)) {
            // set the owning side to null (unless already changed)
            if ($userCourse->getAccount() === $this) {
                $userCourse->setAccount(null);
            }
        }

        return $this;
    }

    public function isBan(): ?bool
    {
        return $this->ban;
    }

    public function setBan(bool $ban): self
    {
        $this->ban = $ban;

        return $this;
    }
}
