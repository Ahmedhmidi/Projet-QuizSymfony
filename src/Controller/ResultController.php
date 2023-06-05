<?php

namespace App\Controller;

use App\Entity\Result;
use App\Repository\AnswersRepository;
use App\Repository\QuestionsRepository;
use App\Repository\QuizRepository;
use App\Repository\ResultRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ResultController extends AbstractController
{

    public function __construct(
        QuestionsRepository $questionsRepository,
        AnswersRepository $answersRepository,
        ResultRepository $resultRepository,
        QuizRepository $quizRepository,
        ManagerRegistry $doctrine
        
    ) {
        $this->QuestionsRepository = $questionsRepository;
        $this->AnswersRepository = $answersRepository;
        $this->ResultRepository = $resultRepository;
        $this->QuizRepository = $quizRepository;
        $this->entityManager = $doctrine->getManager();
    }

    /**
     * @Route("/result", name="app_result")
     */
    public function index(SessionInterface $session,  AnswersRepository $answersRepository): Response
    {
       
        return $this->render('result/index.html.twig', [
          
        ]);
            
    }

          /**
     * [Route('/result', name: 'Calculer')]
     */
    public function Calculer(Request $request): Response
    {
        $result=0;
        $answer = $request->request->all();
        $sessionVal = $this->get('session')->get('ans[]');
        $sessionVal[] = $answer;
        $this->get('session')->set('ans[]', $sessionVal);
        //$size = count($answer);
        //dd($answer);
        
        foreach($answer as $key => $value){
            if($key == "ans"){
                $size = count($value);
                for ($i=0; $i<$size ; $i++) { 
                    if ($value[$i] == '1/') {
                        $result ++;
                    };   
                }
            }
        } 

        $res = $this->getDoctrine()->getRepository(Result::class)->findall();
        $res = new Result();
        $user = $this->getUser();
        //dd($request);
        $quizz = $this->QuizRepository->findAll();
        $entityManger = $this->getDoctrine()->getManager();
        $date = "00:12";

        $res->setEmail($user->getEmail());
        $res->setScore($result);
        $res->setTime($date);
        $res->setName($user->getName());
        $res->setQuiz($res->getQuiz());
        $entityManger->persist($res);
        $entityManger->flush();

        return $this->render('result/index.html.twig', [
            'result' => $result,
            'quizz' => $quizz,
        ]);
    }

}