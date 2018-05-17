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

class ArticleType extends AbstractType
{
    const MIMETYPES = ['image/gif', 'image/jpeg', 'image/png', 'image/svg+xml'];

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class)
            ->add('link', UrlType::class, ['constraints' => [new Url(['checkDNS' => true])]])
            ->add('imgNormal', AltFileType::class)
            ->add('imgContrast',AltFileType::class);
        $builder->get('imgNormal')->addModelTransformer(new FileTransformer($options['entityManager'], self::MIMETYPES));
        $builder->get('imgContrast')->addModelTransformer(new FileTransformer($options['entityManager'], self::MIMETYPES));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection'   => false,
            'entityManager' => null
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
