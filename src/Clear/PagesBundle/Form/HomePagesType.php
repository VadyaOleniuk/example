<?php

namespace Clear\PagesBundle\Form;

use Clear\FileStorageBundle\DataTransformer\AltFileType;
use Clear\FileStorageBundle\DataTransformer\FileTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HomePagesType extends AbstractType
{
    const MIMETYPES = ['image/gif', 'image/jpeg', 'image/png', 'image/svg+xml'];

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new FileTransformer($options['entityManager'], self::MIMETYPES);
        $builder->add('image',AltFileType::class)
            ->add('title', TextType::class)
            ->add('articles', CollectionType::class, [
                'entry_type' => ArticleType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'entry_options' => [ 'entityManager' => $options['entityManager'] ]
            ])
            ->add('address', AddressType::class)
            ->add('mediaAccounts', MediaAccountsType::class)
            ->add('footer', FooterHomePageType::class)
            ->get('image')->addModelTransformer($transformer);
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
