<?php

namespace App\Form;

use App\Entity\Juego;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class JuegoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre')
            ->add('min_jugadores')
            ->add('max_jugadores')
            ->add('alto')
            ->add('ancho')
            ->add('descripcion',TextareaType::class)
            ->add('imagen',FileType::class, [
                'mapped' => false,
                'required'=>false,
            ])
            ->add('editar',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Juego::class,
        ]);
    }
}
