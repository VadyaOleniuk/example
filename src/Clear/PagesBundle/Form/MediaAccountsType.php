<?php

namespace Clear\PagesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Url;

class MediaAccountsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('youtube', UrlType::class, ["constraints" => [new Url(["checkDNS" => true])]])
            ->add('facebook', UrlType::class, ["constraints" => [new Url(["checkDNS" => true])]])
            ->add('google', UrlType::class, ["constraints" => [new Url(["checkDNS" => true])]])
            ->add('pinterest', UrlType::class, ["constraints" => [new Url(["checkDNS" => true])]])
            ->add('instagram', UrlType::class, ["constraints" => [new Url(["checkDNS" => true])]])
            ->add('twitter', UrlType::class, ["constraints" => [new Url(["checkDNS" => true])]])
            ->add('linkedin', UrlType::class, ["constraints" => [new Url(["checkDNS" => true])]])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection'   => false,
        ]);
    }

    public function getBlockPrefix()
    {
        return 'clear_pages_bundle_media_accounts_type';
    }
}
