<?php

namespace App\Controller;

use App\Entity\Quiz;
use App\Entity\Answers;
use App\Entity\Questions;
use App\Repository\AnswersRepository;
use App\Repository\FeedbackRepository;
use App\Repository\QuestionsRepository;
use App\Repository\QuizRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;

/**

*/
class HomeController extends AbstractController
{
    private $QuestionsRepository;
    private $entityManager;

    public function __construct(
        QuestionsRepository $questionsRepository,
        QuizRepository $quizRepository,
        UserRepository $userRepository,
        AnswersRepository $answersRepository,
        FeedbackRepository $feedbackRepository,
        ManagerRegistry $doctrine
    ) {
        $this->QuestionsRepository = $questionsRepository;
        $this->QuizRepository = $quizRepository;
        $this->UserRepository = $userRepository;
        $this->AnswersRepository = $answersRepository;
        $this->FeedbackRepository = $feedbackRepository;
        $this->entityManager = $doctrine->getManager();
    }

    /**
     * @IsGranted("ROLE_USER") 
     * @Route("/home", name="app_home")
     */
    public function index(): Response
    {
        $questions = $this->QuestionsRepository->findAll();
        $quizz = $this->QuizRepository->findAll();
        $feedbacks = $this->FeedbackRepository->findAll();
        $users = $this->UserRepository->findAll();
        $Q = count($quizz);
        $U = count($users);
        $Qs = count($questions);


        return $this->render('home/index.html.twig', [
            'questions' => $questions,
            'quizz' => $quizz,
            'feedbacks' => $feedbacks,
            'Q' => $Q,
            'Qs' => $Qs,
            'U' => $U,
        ]);
    }

    /**
     * [Route('/test/{quiz}', name: 'test_quiz')]
     */
    public function testQuiz(AnswersRepository $answersRepository, Quiz $quiz, Request $request, EntityManagerInterface $entityManager): Response
    {
        $quizz = $this->QuizRepository->findAll();
        $em = $this->getDoctrine()->getManager();
        $repoQuestions = $em->getRepository(Questions::class);
        $answers = $this->getDoctrine()->getRepository(Answers::class)->findBy([/*'id'=>'1'*/]);
            
        $total = $repoQuestions->createQueryBuilder('x')
            ->select('count(x.id)')
            ->getQuery()
            ->getSingleScalarResult();
            
        $session = $request->getSession();
        if ($session->has('ans[]')) {
            $ans = $session->get('ans[]', []);
            $session->set('ans[]', $ans);
        }
        if ($request->isMethod('POST')) {
            $session->set('ans[]', $ans);           
            return $this->redirectToRoute('Result');
        }

        return $this->render('test/index.html.twig', [
            'questions' => $quiz->getQuestions(),
            'answers' => $answers,
            'total' => $total,
            'quizz' => $quizz,
        ]);
    }
}