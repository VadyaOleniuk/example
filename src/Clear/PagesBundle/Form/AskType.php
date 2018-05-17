<?php

namespace Clear\PagesBundle\Form;

use Clear\FileStorageBundle\DataTransformer\AltFileType;
use Clear\FileStorageBundle\DataTransformer\FileTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AskType extends AbstractType
{
    const MIMETYPES = ["image/gif", "image/jpeg", "image/png", "image/svg+xml"];

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new FileTransformer($options["entityManager"], self::MIMETYPES);
        $builder->add("title", TextType::class)
            ->add("content", TextType::class)
            ->add("list", CollectionType::class,[
                "entry_type" => AskListType::class,
                "allow_add" => true,
                "allow_delete" => true,
                "entry_options" => [ "entityManager" => $options["entityManager"] ]
            ])
            ->add("image", AltFileType::class)
            ->get("image")->addModelTransformer($transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            //'data_class' => \Clear\PagesBundle\Model\AskPages::class,
            "csrf_protection"   => false,
            "entityManager" => null
        ]);
    }

    public function getBlockPrefix()
    {
        return "";
    }
}
