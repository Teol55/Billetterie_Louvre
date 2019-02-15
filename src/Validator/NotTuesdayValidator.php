<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class NotTuesdayValidator extends ConstraintValidator
{
    // le musee est fermé les mardis
    public function validate($value, Constraint $constraint)
    {
        $dateTimestamp=$value->getTimestamp();

        /* @var $constraint App\Validator\NotTuesday */

        if(date('l',$dateTimestamp)==='Tuesday') {

            $this->context->buildViolation($constraint->message)
                        ->addViolation();
        }
    }

}
