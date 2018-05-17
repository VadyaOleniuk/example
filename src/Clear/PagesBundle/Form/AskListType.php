<?php

namespace Clear\PagesBundle\Form;

use Clear\FileStorageBundle\DataTransformer\AltFileType;
use Clear\FileStorageBundle\DataTransformer\FileTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Url;

class AskListType extends AbstractType
{
    const MIMETYPES = ["image/gif", "image/jpeg", "image/png", "image/svg+xml"];

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new FileTransformer($options["entityManager"], self::MIMETYPES);
        $builder->add("title", TextType::class)
            ->add("tagtitle", TextType::class)
            ->add("link",  UrlType::class, [ "constraints" =>[new Url(["checkDNS" => true]) ]])
            ->add("img", AltFileType::class)
            ->get("img")->addModelTransformer($transformer);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "csrf_protection"   => false,
            "entityManager" => null,
            "allow_extra_fields" => true,
        ]);
    }

    public function getBlockPrefix()
    {
        return "";
    }
}
