<?php

namespace Clear\ContentBundle\Form;

use Clear\ContentBundle\Entity\Content;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Range;


class StatusType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'status', IntegerType::class, [
                'constraints' => [
                    new Range(
                        [
                        'min' => 0,
                        'max' => 4,
                        'minMessage' => 'Status must be at least {{ limit }}',
                        'maxMessage' => 'status must be at most {{ limit }}'
                        ]
                    )
                ]
            ]
        );
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
            'data_class' => 'Clear\ContentBundle\Entity\Content',
            'csrf_protection'   => false,
            'allow_extra_fields' => true
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return '';
    }

}
