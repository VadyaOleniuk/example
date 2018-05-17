<?php

namespace Clear\PagesBundle\Form;

use Clear\PagesBundle\Entity\Pages;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormBuilderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class)
            ->add('status', IntegerType::class)
            ->add('description', TextType::class)
            ->add('publishedAt', DateTimeType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd HH:mm:ss'
            ])
        ->add("content", CollectionType::class,['allow_add' => true]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Pages::class,
            'csrf_protection'   => false

        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
