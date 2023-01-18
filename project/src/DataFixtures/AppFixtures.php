<?php

namespace App\DataFixtures;

use App\Entity\Answer;
use App\Entity\Channel;
use App\Entity\Question;
use App\Entity\Status;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    /**
     * @var EntityManagerInterface $manager
     */
    private EntityManagerInterface $manager;

    /**
     * @param EntityManagerInterface $manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function load(ObjectManager $manager): void
    {
        $question1 = new Question();
        $question1->setTitle("question 1");
        $question1->setPromoted(true);
        $question1->setStatus(Status::Draft);
        $question1->setCreatedAt(new \DateTime('now'));

        $question2 = new Question();
        $question2->setTitle("Question 2");
        $question2->setPromoted(true);
        $question2->setStatus(Status::Draft);
        $question2->setCreatedAt(new \DateTime('now'));

        $question3 = new Question();
        $question3->setTitle("Question 2");
        $question3->setPromoted(true);
        $question3->setStatus(Status::Draft);
        $question3->setCreatedAt(new \DateTime('now'));

        $reponse1 = new Answer();
        $reponse1->setChannel(Channel::faq);
        $reponse1->setBody("reponse 1");

        $reponse2 = new Answer();
        $reponse2->setChannel(Channel::bot);
        $reponse2->setBody("reponse 2");

        $reponse3 = new Answer();
        $reponse3->setChannel(Channel::bot);
        $reponse3->setBody("reponse 3");

        $reponse4 = new Answer();
        $reponse4->setChannel(Channel::faq);
        $reponse4->setBody("reponse 4");

        $reponse5 = new Answer();
        $reponse5->setChannel(Channel::faq);
        $reponse5->setBody("reponse 5");

        $question1->addAnswer($reponse1);
        $question1->addAnswer($reponse3);
        $question2->addAnswer($reponse2);
        $question2->addAnswer($reponse4);
        $question3->addAnswer($reponse5);
        $manager->persist($question1);
        $manager->persist($question2);
        $manager->persist($question3);
        $manager->persist($reponse1);
        $manager->persist($reponse2);
        $manager->persist($reponse3);
        $manager->persist($reponse4);
        $manager->persist($reponse5);

        $manager->flush();
    }
}
