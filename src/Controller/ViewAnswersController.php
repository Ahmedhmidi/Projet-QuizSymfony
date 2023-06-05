<?php

namespace App\Controller;

use App\Entity\Answers;
use App\Entity\Questions;
use App\Entity\Quiz;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ViewAnswersController extends AbstractController
{
    /**
     * @Route("/view/answers", name="app_view_answers")
     */
    public function index(Request $request): Response
    {

        $questions = $this->getDoctrine()->getRepository(Questions::class)->findBy([/*'id'=>'1'*/]);
        $answers = $this->getDoctrine()->getRepository(Answers::class)->findBy([/*'id'=>'1'*/]);
        $quizz = $this->getDoctrine()->getRepository(Quiz::class)->findBy([/*'id'=>'1'*/]);

            $vrai = '1/';
            $faux = '0/';
            $answer = $request->request->all();
            $sessionVal = $this->get('session')->get('ans[]');
            $sessionVal[] = $answer;
            $this->get('session')->set('ans[]', $sessionVal);
            //dd($sessionVal);
            
            foreach($sessionVal as $key => $value){
                if($key == 0){
                    foreach($value as $keys => $values){
                        if($keys == "ans"){
                            $size = count($values);
                            for ($i=0; $i<$size ; $i++) { 
                                if ($values[$i] == '1/') {
                                    $vrai = $values[$i];
                                }else{
                                    $faux = $values[$i];
                                } 
                            }
                        }
                    }
                }
            } 
    
        return $this->render('view_answers/index.html.twig', [
            'questions' => $questions,
            'answers' => $answers,
            'quizz' => $quizz,
            'vrai' => $vrai,
            'faux' => $faux,
        ]);
    }

}
