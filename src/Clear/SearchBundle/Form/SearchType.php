<?php
namespace Clear\SearchBundle\Form;

use Clear\CompanyBundle\Entity\Company;
use Clear\ContentBundle\Entity\Category;
use Clear\ContentBundle\Entity\Content;
use Clear\ContentBundle\Entity\ContentType;
use Clear\ContentBundle\Entity\Tag;
use Clear\SearchBundle\Model\Search;
use Clear\UserBundle\Entity\Role;
use Clear\UserBundle\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('search')
            ->add('categories', EntityType::class, ['class'=>Category::class, 'multiple'=>true])
            ->add('contentType', EntityType::class, ['class'=>ContentType::class, 'multiple'=>true])
            ->add(
                'tags', EntityType::class, [
                'class'=>Tag::class,
                'multiple'=>true
                ]
            )
            ->add(
                'status', CollectionType::class, [
                'allow_add' => true,
                ]
            )
            ->add('companies', EntityType::class, ['class'=>Company::class, 'multiple'=>true])
            ->add('roles', EntityType::class, ['class'=>Role::class, 'multiple'=>true])
            ->add('authorId', EntityType::class, ['class' => User::class, 'multiple'=>true])
            ->add('from', DateType::class, ['widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('to', DateType::class,  ['widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('isArticle')
            ->add('content', EntityType::class, ['class'=> Content::class])
            ->add('size')
            ->add('page');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
            'data_class' => Search::class,
            'method' => 'GET',
            'csrf_protection'   => false,
            'allow_extra_fields' => true
            )
        );
    }

    public function getBlockPrefix()
    {
        return '';
    }
}