<?php

namespace Clear\ContentBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @Annotation
 */
class CustomFileValidator extends ConstraintValidator
{
    public function validate($protocol, Constraint $constraint)
    {
        if(false === $protocol) {
            $this->context->buildViolation('Upload file is not valid')
                ->addViolation();

            return;
        }
    }
}