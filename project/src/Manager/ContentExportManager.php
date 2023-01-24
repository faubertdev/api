<?php

namespace App\Manager;

use App\Repository\QuestionRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ContentExportManager
{
    /**
     * @var EntityManagerInterface $entityManager
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var EventDispatcherInterface $dispatcher
     */
    private EventDispatcherInterface $dispatcher;

    /**
     * @var ValidatorInterface $validator
     */
    private ValidatorInterface $validator;

    /**
     * @var QuestionRepository $repository
     */
    private QuestionRepository $repository;

    /**
     * @param EntityManagerInterface $entityManager
     * @param EventDispatcherInterface $dispatcher
     * @param ValidatorInterface $validator
     */
    public function __construct(EntityManagerInterface $entityManager, EventDispatcherInterface $dispatcher, ValidatorInterface $validator, QuestionRepository $repository)
    {
        $this->entityManager = $entityManager;
        $this->dispatcher = $dispatcher;
        $this->validator = $validator;
        $this->repository = $repository;
    }

    /**
     * @return StreamedResponse
     */
    public function getExportToCSV()
    {
        $export = $this->repository->getAllAnswers();
        $response = new StreamedResponse();
        $response->setCallback(
            function () use ($export) {
                //Import all questions
                $handle = fopen('php://output', 'r+');
                // Add a row with the names of the columns for the CSV file
                fputcsv($handle, array('Status', 'title', 'Create', 'Promoted', 'update', 'answers'), "\t");
                $header = array();
                print_r($export);

                $stringCreateDate="";
                $stringUpdateDate="";
                foreach ($export as $row) {
                    if ($row['createdAt'] instanceof \DateTime && $row['updatedAt'] instanceof \DateTime)
                    {
                        $stringCreateDate = $row['createdAt']->format('Y-m-d');
                        $stringUpdateDate = $row['updatedAt']->format('Y-m-d');
                    }

                    fputcsv($handle, array(
                        $row['status'],
                        $row['title'],
                        $stringCreateDate,
                        $row['promoted'],
                        $stringUpdateDate,
                        json_encode($row['answers'])
                    ), "\t");
                }
                fclose($handle);
            }
        );

        $response->setStatusCode(200);
        $response->headers->set('Content-Type', 'application/force-download');
        $response->headers->set('Content-Disposition', 'attachment; filename="export.csv"');

        return $response;
    }
}