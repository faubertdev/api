<?php

namespace App\MessageHandler;

use App\Entity\Question;
use App\Message\UpdateQuestionMessage;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class UpdateQuestionMessageHandler implements MessageHandlerInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     */
    public function __invoke(UpdateQuestionMessage $message)
    {
       $queryBuilder = $this->entityManager->createQueryBuilder();

       return $queryBuilder->update(Question::class , 'q')
           ->set('q.title', ':title')
           ->set('q.status', ':status')
           ->where('q.id = :id')
           ->setParameter('title', $message->getQuestion()->getTitle())
           ->setParameter('status', $message->getQuestion()->getStatus())
           ->setParameter('id', $message->getQuestion()->getId())
           ->getQuery()
           ->execute();

  //$result = $query->execute();
  //     $this->entityManager->getRepository(QuestionRepository::class)
    }
}