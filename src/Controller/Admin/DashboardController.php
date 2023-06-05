<?php

namespace App\Controller\Admin;

use App\Entity\College;
use App\Entity\Feedback;
use App\Entity\Questions;
use App\Entity\Quiz;
use App\Entity\Result;
use App\Entity\User;
use App\Repository\CollegeRepository;
use App\Repository\QuizRepository;
use App\Repository\UserRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @IsGranted("ROLE_ADMIN") 
 * @Route("/admin")
 */
class DashboardController extends AbstractDashboardController
{
    public function __construct(
        QuizRepository $quizRepository,
        CollegeRepository $collegeRepository,
        UserRepository $userRepository,
        ManagerRegistry $doctrine
    ) {
        $this->QuizRepository = $quizRepository;
        $this->CollegeRepository = $collegeRepository;
        $this->UserRepository = $userRepository;
        $this->entityManager = $doctrine->getManager();
    }
    
    /**
     * @Route("/admin", name="index")
     */
    public function index(): Response
    {
         $u = 0; $qu = 0; $s = 0; $maxS = 0;
        $users = $this->getDoctrine()->getRepository(User::class)->findBy([/*'id'=>'1'*/]);   
        $questions = $this->getDoctrine()->getRepository(Questions::class)->findBy([/*'id'=>'1'*/]);
        $quiz = $this->QuizRepository->findAll();
        $results = $this->getDoctrine()->getRepository(Result::class)->findAll();
        $Q =  count($quiz);
        $r = count($results);
        for ($i = 0; $i < count($users); $i++) {
            $u++;
        }
        for ($i = 0; $i < count($questions); $i++) {
            $qu++;
        }

        foreach ($results as $result) {
            $s = $s + $result->getScore();
        }
        $s = round(($s / $r), 2) ;

        foreach ($results as $result) {
            if ($result->getScore() > $maxS) {
                $maxS = $result->getScore();
                $maxN = $result->getName();
            }
        }

        $cols = $this->CollegeRepository->countByVille();
        $colleges = $this->CollegeRepository->findAll();
        $quizzes = $this->QuizRepository->countByQuiz();
        $quizes = $this->QuizRepository->findAll();

        $collegeAbrv = [];
        
        $collegeCount = [];
        foreach ($cols as $col){
            $collegeAbrv[] = $col['villeCollege'];
        }
        foreach ($colleges as $college){            
            // $collegeAbrv[] = $college->getVille();
            $collegeCount[] = count($college->getUsers());
        }

        $quizTitle = [];
        
        $quizCount = [];
        foreach ($quizzes as $quizze){
            $quizTitle[] = $quizze['quiz'];
        }
        foreach ($quizes as $quize){            
            // $collegeAbrv[] = $college->getVille();
            $quizCount[] = count($quize->getResults());
        }

        $gens = $this->UserRepository->countByGender();
        $genderVal = [];
        $genderCount = [];

        foreach ($gens as $gen){
            $genderVal[] = $gen['gen'];
            $genderCount[] = $gen['count'];
        }

        $ags = $this->UserRepository->countByAge();
        $ageVal = [];
        $ageCount = [];

        foreach ($ags as $ag){
            $ageVal[] = $ag['ag'];
            $ageCount[] = $ag['count'];
        }


        return $this->render('bundles/EasyAdminBundle/welcome.html.twig', [
            'u' => $u, 's' => $s,
            'qu' => $qu,
            'maxN' => $maxN,
            'results' => $results,
            'Q' => $Q,
            'genderVal' => json_encode($genderVal),
            'genderCount' => json_encode($genderCount),
            'ageVal' => json_encode($ageVal),
            'ageCount' => json_encode($ageCount),
            'collegeAbrv' => json_encode($collegeAbrv),
            'collegeCount' => json_encode($collegeCount),
            'quizTitle' => json_encode($quizTitle),
            'quizCount' => json_encode($quizCount),

        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Quiz');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::subMenu('Learners', 'fa fa-user')->setSubItems([
            MenuItem::linkToCrud('Show Learners', 'fa fa-eye', User::class)
        ]);

        yield MenuItem::subMenu('Quiz', 'fa fa-list')->setSubItems([
            MenuItem::linkToCrud('Show Quiz', 'fa fa-eye', Quiz::class),
            MenuItem::linkToCrud('Create Quiz', 'fa fa-plus', Quiz::class)->setAction(Crud::PAGE_NEW),
        ]);

        yield MenuItem::subMenu('Questions', 'fa fa-question')->setSubItems([
            MenuItem::linkToCrud('Show Questions', 'fa fa-eye', Questions::class),
            MenuItem::linkToCrud('Add Question', 'fa fa-plus', Questions::class)->setAction(Crud::PAGE_NEW),
        ]);

        yield MenuItem::subMenu('Colleges', 'fa fa-university')->setSubItems([
            MenuItem::linkToCrud('Show Colleges', 'fa fa-eye', College::class),
            MenuItem::linkToCrud('Add College', 'fa fa-plus', College::class)->setAction(Crud::PAGE_NEW),
        ]);

        yield MenuItem::subMenu('Feedbacks', 'fa fa-commenting-o')->setSubItems([
            MenuItem::linkToCrud('Show Feedbacks', 'fa fa-eye', Feedback::class),
        ]);

        yield MenuItem::linkToRoute('Profile', 'fa fa-user', 'Profile');

        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}