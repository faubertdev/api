<?php

namespace App\Entity;
use App\Repository\AnswerRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Table]
#[ORM\Entity(repositoryClass: AnswerRepository::class)]
class Answer
{
    #[Groups(['question', 'answer'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[Groups(['question', 'answer'])]
    #[ORM\Column(type: "string", length: 255)]
    private string $channel;

    #[Groups(['question', 'answer'])]
    #[ORM\Column(type: "string", length: 255)]
    private string $body;

    #[Groups('answer')]
    #[ORM\ManyToOne(targetEntity: Question::class, cascade: ["all"], inversedBy: "answers")]
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

    public function setChannel(string $channel): self
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

    /**
     * @return mixed
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * @param mixed $question
     */
    public function setQuestion($question): void
    {
        $this->question = $question;
    }
}