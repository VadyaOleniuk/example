<?php

namespace Clear\PagesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Url;

class FooterHomePageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('termsCondition', UrlType::class, ["constraints" => [new Url(["checkDNS" => true])]])
            ->add('privacy', UrlType::class, ["constraints" => [new Url(["checkDNS" => true])]])
            ->add('accessibility', UrlType::class, ["constraints" => [new Url(["checkDNS" => true])]]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection'   => false,
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
