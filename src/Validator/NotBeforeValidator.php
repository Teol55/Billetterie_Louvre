<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class NotBeforeValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        //Réservation interdite pour les jours antérieurs

        /* @var $constraint App\Validator\NotBefore */
        $dateTimestamp=$value->getTimestamp();

        if( $dateTimestamp <time() && !date_format($value,"d-m-Y")==date("d-m-Y") ) {

            $this->context->buildViolation($constraint->message)
                    ->addViolation();
        }
    }
}

