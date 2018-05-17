<?php

namespace Clear\FileStorageBundle\DataTransformer;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AltFileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('file',FileType::class,['required' => true])
            ->add('alt', TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'csrf_protection'   => false,
                'entityManager' => null,
                'allow_extra_fields' => true,
            )
        );

    }

    public function getBlockPrefix()
    {
        return '';
    }
}
