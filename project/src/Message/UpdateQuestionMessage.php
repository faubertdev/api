<?php

namespace App\Message;

use App\Entity\Question;

class UpdateQuestionMessage
{
    /**
     * @var Question $question
     */
    private Question $question;

    /**
     * @var \DateTime
     *
     */
    private \DateTime $updateTime;

    /**
     * @param Question $question
     * @param \DateTime $updateTime
     */
    public function __construct(Question $question, \DateTime $updateTime)
    {
        $this->question = $question;
        $this->updateTime = new \DateTime('now');
    }

    /**
     * @return Question
     */
    public function getQuestion(): Question
    {
        return $this->question;
    }

    /**
     * @return \DateTime
     */
    public function getUpdateTime(): \DateTime
    {
        return $this->updateTime;
    }
}