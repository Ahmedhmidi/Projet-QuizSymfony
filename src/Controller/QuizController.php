<?php

namespace App\Controller;

use App\Entity\Questions;
use App\Repository\QuestionsRepository;
use App\Repository\QuizRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuizController extends AbstractController
{
    public function __construct(
        QuestionsRepository $questionsRepository,
        QuizRepository $quizRepository
    ) {
        $this->QuestionsRepository = $questionsRepository;
        $this->QuizRepository = $quizRepository;
    }

    /**
     * @Route("/quiz", name="app_quiz")
     */
    public function index(): Response
    {   
        $quizz = $this->QuizRepository->findAll();
        $questions = $this->QuestionsRepository->findAll();

        $em = $this->getDoctrine()->getManager();
        $repoQuestions = $em->getRepository(Questions::class);
        
        //Query how many rows are there in the questions table
        $total = $repoQuestions->createQueryBuilder('x')
            // Filter by some parameter if you want
            ->select('count(x.id)')
            ->getQuery()
            ->getSingleScalarResult();
        
        return $this->render('quiz/index.html.twig', [
            'total' => $total,
            'quizz' => $quizz,
            'questions' => $questions,
        ]);
    }   
}
