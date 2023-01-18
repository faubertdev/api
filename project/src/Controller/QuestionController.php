<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Question;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class QuestionController extends AbstractController
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    #[Route('/api/questions', name: 'list_question', methods: 'GET')]
    public function getAllQuestion(QuestionRepository $repository, SerializerInterface $serializer): JsonResponse
    {
        $questionList = $repository->findAll();
        $jsonQuestionList = $serializer->serialize($questionList, 'json', ['groups' => 'question']);

        return new JsonResponse($jsonQuestionList, Response::HTTP_OK, [], true);
    }

    #[Route('/api/question/add', name: 'add_question', methods: 'POST' )]
    public function createOneQuestion(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager)
    {
        $question = $serializer->deserialize($request->getContent(), Question::class,'json');
        $entityManager->persist($question);
        $entityManager->flush();

        $jsonAnswer = $serializer->serialize($question, 'json', ['groups' => 'question']);

        return new JsonResponse($jsonAnswer, Response::HTTP_CREATED, [], true);
    }

    #[Route('/api/question/{id}/edit', name: 'edit_question', methods: 'PUT' )]
    public function editQuestion(Question $question, SerializerInterface $serializer, Request $request, EntityManagerInterface $entityManager)
    {
        $updatedQuestion = $serializer->deserialize($request->getContent(), Question::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $question]);

        $entityManager->persist($updatedQuestion);
        $entityManager->flush();

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }

    #[Route('/api/question/{id}/show', name: 'show_question', methods: 'GET' )]
    public function showOneQuestion(Question $question, SerializerInterface $serializer)
    {
        $jsonQuestion = $serializer->serialize($question, 'json', ['groups' => 'question']);

        return new JsonResponse($jsonQuestion, Response::HTTP_OK, [],true);
    }

    #[Route('/api/question/{id}/delete', name: 'delete_question', methods: 'DELETE' )]
    public function deleteOneQuestion(Question $question, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($question);
        $entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}