<?php

namespace App\Tests;

use App\Entity\Answer;
use App\Entity\Channel;
use App\Entity\Question;
use PHPUnit\Framework\TestCase;

class AnswerPostTest extends TestCase
{
    public function testSomething(): void
    {
        $this->assertTrue(true);
    }

    public function testIsTrue()
    {
        $answer = new Answer();
        $question = new Question();

        $answer
            ->setChannel(Channel::faq)
            ->setBody('message')
            ->setQuestion($question)
        ;

        $this->assertTrue($answer->getChannel() === Channel::faq);
        $this->assertTrue($answer->getBody() === 'message');
        $this->assertTrue($answer->getQuestion() === $question);
    }

    public function testIsFalse()
    {
        $answer = new Answer();
        $question = new Question();

        $answer
            ->setChannel(Channel::bot)
            ->setBody('komessage')
            ->setQuestion(null);


        $this->assertFalse($answer->getChannel() === Channel::faq);
        $this->assertFalse($answer->getBody() === 'false');
        $this->assertFalse($answer->getQuestion() === $question);
    }

    public function testIsEmpty()
    {
        $answer = new Answer();

        $this->assertEmpty($answer->getBody());
        $this->assertEmpty($answer->getChannel());
        $this->assertEmpty($answer->getQuestion());
    }
}
