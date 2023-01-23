<?php

namespace App\Entity;

use App\Repository\HistoricQuestionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HistoricQuestionRepository::class)]
class HistoricQuestion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'historicQuestions')]
    private ?Question $question = null;

    #[ORM\Column]
    private ?int $nbVersion = null;

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

    public function getNbVersion(): ?int
    {
        return $this->nbVersion;
    }

    public function setNbVersion(int $nbVersion): self
    {
        $this->nbVersion = $nbVersion;

        return $this;
    }
}
