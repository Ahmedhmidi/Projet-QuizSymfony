<?php

namespace App\Form;

use App\Entity\Feedback;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FeedbackType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Name', TextType::class, [
                'attr' => [
                    'placeholder' => ' Name'
                ]
            ])
            ->add('Email', EmailType::class, [
                'attr' => [
                    'placeholder' => ' Email',
                ]
            ])
            ->add('Subject', TextType::class, [
                'attr' => [
                    'placeholder' => ' Subject'
                ]
            ])
            ->add('Feedbacks', TextareaType::class, [
                'attr' => [
                    'placeholder' => ' Your Feedback'
                ]
            ])
            ->add('parentid', HiddenType::class, [
                'mapped' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Feedback::class,
        ]);
    }
}
