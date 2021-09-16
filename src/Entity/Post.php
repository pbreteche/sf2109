<?php

namespace App\Entity;

use App\Repository\PostRepository;
use App\Validator\Demo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass=PostRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Post
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Demo()
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     * @Assert\Length(min=20)
     */
    private $body;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Assert\LessThan(value="today", groups="publish")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPublished = false;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private $writtenBy;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getIsPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(bool $isPublished): self
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    public function getWrittenBy(): ?User
    {
        return $this->writtenBy;
    }

    public function setWrittenBy(?User $writtenBy): self
    {
        $this->writtenBy = $writtenBy;

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersistCallback()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    /**
     * @Assert\IsTrue(message="body_larger_title")
     */
    public function isBodyLargerThanTitle(): bool
    {
        return mb_strlen($this->body) > mb_strlen($this->title);
    }

    /**
     * @Assert\Callback()
     */
    public function isBodyLargerThanTitle2(ExecutionContextInterface $context, $payload)
    {
        if (mb_strlen($this->body) > mb_strlen($this->title)) {
            return;
        }

        $context->buildViolation('body_larger_title')
            ->atPath('body')
            ->addViolation()
        ;
    }
}
