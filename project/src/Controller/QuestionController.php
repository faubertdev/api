<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Question;
use App\Manager\ContentExportManager;
use App\Message\UpdateQuestionMessage;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class QuestionController extends AbstractController
{
    /**
     * @var SerializerInterface $serializer
     */
    private SerializerInterface $serializer;

    /**
     * @var MessageBusInterface $bus
     */
    private MessageBusInterface $bus;

    /**
     * @param SerializerInterface $serializer
     * @param MessageBusInterface $bus
     */
    public function __construct(SerializerInterface $serializer, MessageBusInterface $bus)
    {
        $this->serializer = $serializer;
        $this->bus = $bus;
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
        $oldquestion = $entityManager->getRepository(Question::class)->find($question->getId());
        $updatedQuestion = $serializer->deserialize($request->getContent(), Question::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $question]);
        $this->bus->dispatch(new UpdateQuestionMessage($oldquestion));
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

    #[Route('/api/export', name: 'api_export', options: ['expose' => true], methods: 'GET')]
    public function export(EntityManagerInterface $entityManager, ContentExportManager $exportManager)
    {
        return $exportManager->getExportToCSV();
    }
}
