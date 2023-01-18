<?php

namespace App\Entity;

use App\Entity\Status;
use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Table]
#[ORM\Entity(repositoryClass: QuestionRepository::class)]
class Question
{
    #[Groups(['question', 'answer'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['question', 'answer'])]
    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[Groups(['question', 'answer'])]
    #[ORM\Column]
    private ?bool $promoted = null;

    #[Groups(['question', 'answer'])]
    #[ORM\Column(type: "string", length: 255)]
    private string $status;

    #[Groups(['question', 'answer'])]
    #[ORM\Column(name: "created_at", type: "datetime")]
    private \DateTime $createdAt;

    #[Groups(['question', 'answer'])]
    #[ORM\Column(name: "updated_at", type: "datetime", nullable: true)]
    private \DateTime $updatedAt;

    #[Groups(['question'])]
    #[ORM\OneToMany(mappedBy: "question", targetEntity: Answer::class, cascade:["persist", "remove"])]
    private Collection $answers;

    #[ORM\OneToMany(mappedBy: 'question', targetEntity: HistoricQuestion::class)]
    private Collection $historicQuestions;

    public function __construct()
    {
        $this->answers = new ArrayCollection();
        $this->status = Status::Draft;
        $this->createdAt = new \DateTime('now');
        $this->historicQuestions = new ArrayCollection();
    }

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

    public function isPromoted(): ?bool
    {
        return $this->promoted;
    }

    public function setPromoted(bool $promoted): self
    {
        $this->promoted = $promoted;

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

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt(\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return Collection
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(Answer $answer)
    {
        $this->answers->add($answer);
        $answer->setQuestion($this);
    }

    /**
     * @return Collection<int, HistoricQuestion>
     */
    public function getHistoricQuestions(): Collection
    {
        return $this->historicQuestions;
    }

    public function addHistoricQuestion(HistoricQuestion $historicQuestion): self
    {
        if (!$this->historicQuestions->contains($historicQuestion)) {
            $this->historicQuestions->add($historicQuestion);
            $historicQuestion->setQuestion($this);
        }

        return $this;
    }

    public function removeHistoricQuestion(HistoricQuestion $historicQuestion): self
    {
        if ($this->historicQuestions->removeElement($historicQuestion)) {
            // set the owning side to null (unless already changed)
            if ($historicQuestion->getQuestion() === $this) {
                $historicQuestion->setQuestion(null);
            }
        }

        return $this;
    }
}
