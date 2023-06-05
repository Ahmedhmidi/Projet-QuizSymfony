<?php

namespace App\Controller;

use App\Entity\Answers;
use App\Entity\Questions;
use App\Repository\AnswersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
  
    /**
     * @Route("/test", name="app_test")
     */
    public function index(AnswersRepository $answersRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $em = $this->getDoctrine()->getManager();
        $repoQuestions = $em->getRepository(Questions::class);
        $total = $repoQuestions->createQueryBuilder('x')
            ->select('count(x.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $questions = $this->getDoctrine()->getRepository(Questions::class)->findBy([/*'id'=>'1'*/]);
        $answers = $this->getDoctrine()->getRepository(Answers::class)->findBy([/*'id'=>'1'*/]);

        return $this->render('test/index.html.twig', [
            'questions' => $questions,
            'answers' => $answers,
            'total' => $total,
           
        ]);
    }

    

    
}