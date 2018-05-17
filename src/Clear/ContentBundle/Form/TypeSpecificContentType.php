<?php

namespace Clear\ContentBundle\Form;

use Clear\ContentBundle\Form\FormsConstructor\ResourceType;
use Clear\ContentBundle\Form\FormsConstructor\DynamicFormType;
use PharIo\Manifest\Url;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Collection;

class TypeSpecificContentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'typeValues', CollectionType::class, [
            'allow_add' => true,
            'allow_delete' => true,
            'entry_type'=> DynamicFormType::class,
            'entry_options'  => [
                'contentType'  => $options['contentType'],
                'entityManager' => $options['entityManager'],

            ]
            ]
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
            'allow_extra_fields' => true,
            'contentType' => null,
            'entityManager' => null,
            'content' => null,
            'csrf_protection'   => false
            )
        );
    }

    public function getBlockPrefix()
    {
        return 'content';
    }
}
