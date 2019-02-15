<?php

namespace App\Validator;

use App\Entity\Ticket;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class NotPriceDayAfter14Validator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint App\Validator\NotPriceDayAfter14 */

        if(!$value instanceof Ticket){
            throw new \LogicException();
        }
//Réservation journée impossible aprés 14h le jour même

        if(date_format($value->getDateVisit(),"d-m-Y")==date("d-m-Y") && date('h-i',time())> date('h-i',mktime(8,0,0))&& $value->getTypeTicKet()=='tarifJournee') {
            $this->context->buildViolation($constraint->message)
                                ->addViolation();
        }
    }


}