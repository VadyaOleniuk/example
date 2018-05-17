<?php

namespace Clear\ContentBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class CustomFile extends Constraint
{
    public $message = 'You need correct file';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}