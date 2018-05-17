<?php

namespace Clear\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use FOS\UserBundle\Util\LegacyFormHelper;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;

class ProfileType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $constraintsOptions = array(
            'message' => 'fos_user.current_password.invalid',
        );

        if (!empty($options['validation_groups'])) {
            $constraintsOptions['groups'] = array(reset($options['validation_groups']));
        }

        $builder->add(
            'username',
            TextType::class,
            [
                'constraints' => [
                    new Length(
                        [
                            'min' => 3,
                            'minMessage' => 'Your name must be at least {{ limit }} characters long',
                        ]
                    )
                ]
            ]
        )
            ->add(
                'email',
                EmailType::class,
                [
                    'constraints' => [
                        new Email(
                            [
                                'message' => "The email {{ value }} is not a valid email"
                            ]
                        )
                    ]
                ]
            )
            ->add('jobTitle', TextType::class);
        if ($options["pass"]) {
            $builder->add(
                'current_password',
                LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\PasswordType'),
                array(
                    'label' => 'form.current_password',
                    'translation_domain' => 'FOSUserBundle',
                    'mapped' => false,
                    'constraints' => new UserPassword($constraintsOptions)
                )
            )
                ->add(
                    'plainPassword',
                    LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\RepeatedType'),
                    array(
                        'type' => LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\PasswordType'),
                        'options' => array('translation_domain' => 'FOSUserBundle'),
                        'first_options' => array('label' => 'form.new_password'),
                        'second_options' => array('label' => 'form.new_password_confirmation'),
                        'invalid_message' => 'fos_user.password.mismatch',
                        'constraints' => [
                            new Length(
                                [
                                    'min' => 6,
                                    'minMessage' => 'Your password must be at least {{ limit }} characters long',
                                ]
                            )
                        ],
                    )
                );
        }

        $builder->add(
            'lastName', TextType::class, [
                'constraints' => [
                    new Length(
                        [
                        'min' => 3,
                        'minMessage' => 'Your last name must be at least {{ limit }} characters long',
                        ]
                    )
                ]
            ]
        )
            ->add('function', TextType::class);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
            'data_class' => 'Clear\UserBundle\Entity\User',
            'csrf_protection'   => false,
            'pass' =>false,
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'clear_userbundle_user';
    }

}
