<?php

namespace App\Entity;
use App\Repository\AnswerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnswerRepository::class)]
class Answer
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(type: "string", length: 255)]
    private string $channel;

    #[ORM\Column(type: "string", length: 255)]
    private string $body;

    #[ORM\ManyToOne(targetEntity: Question::class, inversedBy: "answers")]
    private $question;

    public function __construct()
    {
        $this->channel = Channel::faq;
    }


    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    public function getChannel(): string
    {
        return $this->channel;
    }

    public function setStatus(string $channel): self
    {
        $this->channel = $channel;

        return $this;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }
}