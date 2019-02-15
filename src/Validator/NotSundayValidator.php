<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class NotSundayValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        //le musee est ouvert le dimanche mais fermé à la réservation


        $dateTimestamp=$value->getTimestamp();

        /* @var $constraint App\Validator\NotSunday */

        if(date('l',$dateTimestamp)==='Sunday') {
            $this->context->buildViolation($constraint->message)

                ->addViolation();
        }
    }


}