<?php

namespace App\Controller;

use App\Entity\Feedback;
use App\Entity\Quiz;
use App\Form\FeedbackType;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\Time;

class FeedbackController extends AbstractController
{
    /**
     * @Route("/feedback", name="app_feedback")
     */
    public function index(Request $request): Response
    {
        $quiz = $this->getDoctrine()->getRepository(Quiz::class)->findBy([/*'id'=>'1'*/]);
        $feedback  = new Feedback();
        $form = $this->createForm(FeedbackType::class, $feedback);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $feedback->setCreatedAt(new DateTime());

            $parentid = $form->get("parentid")->getData();

            $em = $this->getDoctrine()->getManager();

            // $parent = $em->getRepository(Feedback::class)->find($parentid);
            // $feedback->setParent($parent);
            $em->persist($feedback);
            $em->flush();

            $this->addFlash('info', 'Votre feedback a été bien envoyé');
            return $this->redirectToRoute('Feedback');
        }

        return $this->render('feedback/index.html.twig', [
            'feedbackForm' => $form->createView(),
        ]);
    }
}
