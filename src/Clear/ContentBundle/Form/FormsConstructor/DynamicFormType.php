<?php

namespace Clear\ContentBundle\Form\FormsConstructor;

use Clear\FileStorageBundle\DataTransformer\AltFileType;
use Clear\FileStorageBundle\DataTransformer\FileTransformer;
use Clear\ContentBundle\Validator\Constraints\CustomFile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class DynamicFormType extends AbstractType
{
    private $constraintMap = [
        'url' => Url::class,
        'textarea' => NotBlank::class,
        'file' => CustomFile::class
    ];

    const MIMETYPES = ["application/pdf", "application/x-pdf", "application/msword", "application/xml",
        "application/vnd.openxmlformats-officedocument.wordprocessingml.document", "application/vnd.oasis.opendocument.text"];

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $contentType = $options['contentType'];
        $transformer = new FileTransformer($options['entityManager'], self::MIMETYPES);

        if($contentType && $contentType->getForm()) {
            foreach ($contentType->getForm()['form']as $key => $field) {

                $variable = $this->getValidationOption($field['option']);
                $builder->add($key, $field['type'], ['constraints' => $variable]);
                if($field['type'] == AltFileType::class) {
                    $builder->get($key)->addModelTransformer($transformer);
                }
            }
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
            'csrf_protection'   => false,
            'contentType' => null,
            'entityManager' => null,
            'content' =>null,
            'allow_extra_fields' => true,
            )
        );
    }

    public function getBlockPrefix()
    {
        return '';
    }

    protected function getValidationOption($options)
    {
        $options = explode(',', $options);

        $variable = [];
        foreach ($options as $option) {
            switch ($option) {
            case 'url': $variable[] =  new $this->constraintMap['url'](['checkDNS' => true]);
                break;
            case 'textarea': $variable[] =  new $this->constraintMap['textarea'];
                break;
            case 'file': $variable[] =  new $this->constraintMap['file'];
                break;
            }
        }

        return $variable;
    }
}
