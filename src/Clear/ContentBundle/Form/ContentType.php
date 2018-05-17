<?php

namespace Clear\ContentBundle\Form;

use Clear\ContentBundle\Entity\Category;
use Clear\ContentBundle\Entity\Content;
use Clear\ContentBundle\Entity\Tag;
use Clear\FileStorageBundle\Entity\FileStorage;
use Clear\LanguageBundle\Entity\Language;
use Faker\Provider\File;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ContentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('title', TextType::class)

            ->add('content', TextType::class)

            ->add('typeValues', CollectionType::class, ['allow_add' => true])
            ->add('status')
            ->add('description', TextType::class)

            ->add(
                'publishedAt', DateTimeType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd HH:mm:ss'
                ]
            )
            ->add('language', EntityType::class, ['class'=> Language::class])
            ->add('imageFile', FileType::class, ['required' => true])
            ->add('alt', TextType::class)
            ->add('categories', null)
            ->add('children')
            ->add(
                'isArticle', ChoiceType::class, [
                'choices' => [
                    'Yes' => true,
                    'No' => false,
                ]
                ]
            )
            ->add(
                'contentType', EntityType::class, [
                'class' => \Clear\ContentBundle\Entity\ContentType::class,
                ]
            )
            ->add('tags', null)
            ->add('roles', null)
            ->add('companies', null);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
            'data_class' => 'Clear\ContentBundle\Entity\Content',
            'csrf_protection'   => false
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'content';
    }


}
