<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class DayOffCloseValidator extends ConstraintValidator
{
    public function validate($date, Constraint $constraint)
    {
        // le musée est fermé le 1er Mai le 1er Novembre et le 25Décembre
        /* @var $constraint App\Validator\DayOffClose */
        $dateString = date_format($date, "d-m");

        if($dateString == '25-12' or $dateString == '01-11' or $dateString == '01-05') {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', date_format($date,'d-m-Y'))
                ->addViolation();
        }
    }
}



