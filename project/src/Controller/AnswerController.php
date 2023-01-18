<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Question;
use App\Repository\AnswerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class AnswerController extends AbstractController
{
    #[Route('/api/question/{id}/answers', name: 'list_answer', methods: 'GET')]
    public function listAnswer(Question $question, AnswerRepository $answerRepository, SerializerInterface $serializer): JsonResponse
    {
        $answerList = $answerRepository->findBy(['question' => $question->getId()]);
        $jsonAnswerList = $serializer->serialize($answerList, 'json', ['groups' => 'answer']);

        return new JsonResponse($jsonAnswerList, Response::HTTP_OK, [], true);
    }

    #[Route('/api/question/{question}/answer/{answer}/show', name: 'show_answer', methods: 'GET')]
    public function showAnswer(Question $question, Answer $answer, SerializerInterface $serializer)
    {
        $jsonAnswer = $serializer->serialize($answer, 'json', ['groups' => 'answer']);

        return new JsonResponse($jsonAnswer, Response::HTTP_OK, [],true);
    }

    #[Route('/api/question/{question}/answer/add', name: 'add_answer' ,methods: 'POST')]
    public function addAnswer(Question $question,  Request $request, EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator, SerializerInterface $serializer): JsonResponse
    {
        /*  @var Answer $answer   */
        $answer = $serializer->deserialize($request->getContent(), Answer::class,'json');
        $answer->setQuestion($question);
        $entityManager->persist($answer);
        $entityManager->flush();

        $jsonAnswer = $serializer->serialize($answer, 'json', ['groups' => 'answer']);

        return new JsonResponse($jsonAnswer, Response::HTTP_CREATED, [], true);
    }

    #[Route('/api/question/{question}/answer/{answer}/edit', name: 'edit_answer', methods: 'PUT')]
    public function editAnswer(Answer $answer, Request $request, SerializerInterface $serializer, AnswerRepository $answerRepository, EntityManagerInterface $entityManager)
    {
        $updatedAnswer = $serializer->deserialize($request->getContent(), Answer::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $answer]);

        $entityManager->persist($updatedAnswer);
        $entityManager->flush();

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }

    #[Route('/api/answer/{answer}', name: 'delete_answer', methods: 'DELETE')]
    public function deleteAnswer(Answer $answer, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($answer);
        $entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
