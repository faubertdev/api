<?php

namespace App\Tests;

use App\Entity\Question;
use App\Entity\Status;
use PHPUnit\Framework\TestCase;

class QuestionPostTest extends TestCase
{
    public function testSomething(): void
    {
        $this->assertTrue(true);
    }

    public function testIsTrue()
    {
        $question = new Question();

        $question
            ->setTitle("title")
            ->setPromoted(true)
            ->setStatus(Status::Draft)
            ->setCreatedAt(new \DateTime('now'))
        ;
        $this->assertTrue($question->getTitle() === "title");
        $this->assertTrue($question->isPromoted() === true);
        $this->assertTrue($question->getCreatedAt() === new \DateTime('now'));
        $this->assertTrue($question->getStatus() === Status::Draft);
    }

    public function testIsFalse()
    {
        $question = new Question();

        $question
            ->setTitle("title")
            ->setPromoted(true)
            ->setStatus(Status::Draft)
            ->setCreatedAt(new \DateTime('now'))
        ;

        $this->assertFalse($question->getTitle() === "dabou");
        $this->assertFalse($question->isPromoted() === false);
        $this->assertFalse($question->getCreatedAt() === new \DateTime("2000-12-21"));
        $this->assertFalse($question->getStatus() === Status::Published);
    }

    public function testIsEmpty()
    {
        $question = new Question();

        $this->assertEmpty($question->getTitle());
        $this->assertEmpty($question->getCreatedAt());
        $this->assertEmpty($question->getStatus());
        $this->assertEmpty($question->isPromoted());
    }
}
