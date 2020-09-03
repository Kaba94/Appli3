<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class EditFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('nom', TextType::class, [
            'constraints' => [
                new NotBlank(['message' => 'Nom Obligatoire '])
            ]
        ])
        ->add('description', TextareaType::class, [
            'constraints' => [
                new NotBlank(['message' => 'Description Obligatoire '])
            ]
        ])
        ->add('lieu', TextType::class, [
            'constraints' => [
                new NotBlank(['message' => 'Nom Obligatoire '])
            ]
        ])
        ->add('date', DateType::class, [
            'constraints' => [
                new NotBlank(['message' => 'Selectioner une date !']),
                
            ],
            "widget" => 'single_text'
            
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
