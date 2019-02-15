<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 *
 */
class NotPriceDayAfter14 extends Constraint
{
    /*
     * Any public properties become valid options for the annotation.
     * Then, use these in your validator class.
     */
    public $message = 'Aprés 14h , vous devez prendre un billet demi-journée';

    public function getTargets()
    {
        return Constraint::CLASS_CONSTRAINT;
    }
}