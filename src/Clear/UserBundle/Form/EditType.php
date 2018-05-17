<?php

namespace Clear\UserBundle\Form;

use Clear\UserBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', TextType::class)
            ->add('email', EmailType::class)
            ->add('function', TextType::class)
            ->add('jobTitle', TextType::class)
            ->add('plainPassword', PasswordType::class)
            ->add('company')
            ->add('roleUsers')
            ->add('lastName', TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {

        $resolver->setDefaults(
            array(
            'data_class' => User::class,
            'csrf_protection'   => false
            )
        );
    }



    public function getBlockPrefix()
    {
        return 'clear_user_bundle_edit_type';
    }
}
