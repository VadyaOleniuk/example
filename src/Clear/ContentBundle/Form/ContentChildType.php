<?php

namespace Clear\ContentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ContentChildType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->remove('anonymousContent');
    }

    public function getParent()
    {
        return ContentType::class;
    }
}