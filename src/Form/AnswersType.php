<?php

namespace App\Form;

use App\Entity\Answers;
use App\Repository\AnswersRepository;
use App\Repository\QuestionsRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnswersType extends AbstractType
{
    

    /**
     * @var AnswersRepository $answersRepository
     */
    protected $answersRepository;

    /**
     * @var QuestionsRepository $questionsRepository
     */
    protected $questionsRepository;

    public function __construct(AnswersRepository $answersRepository, QuestionsRepository $questionsRepository)
    {
        $this->AnswersRepository = $answersRepository;
        $this->QuestionsRepository = $questionsRepository;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    { 
        $builder
            // ->add('NumberQ')
            // ->add('rightAns')
            ->add('Answer', EntityType::class, [   
                'class' => Answers::class,

                // 'choice_value' => function(Answers $answer = null) {
                //     $answers = $this->AnswersRepository->findAll();
                //     $questions = $this->QuestionsRepository->findAll();
                //     $dataAnswers = [];
                //     foreach ($questions as $question){
                //         foreach ($answers as $answer){
                //             if ($answer->getNumberQ() == $question->getNumberQ()) {
                //                 return  $answer->getAnswer();
                //             }
                //         }
                //     }
                    
                // },

                // 'choice_value' => function (Answers $entity = null, Questions $questions) {
                //     //$answers = $this->AnswersRepository->findAll();
                //     //$questions = $this->QuestionsRepository->findAll();
                //     if($entity->getNumberQ() == $questions->getNumberQ()){
                //         return $entity ? $entity->getId() : '';
                //     }
                // },             
                'label' => false,
                'expanded' => true,
                //'multiple' => true
                ])
                ->add('Submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Answers::class,
        ]);
    }
}
