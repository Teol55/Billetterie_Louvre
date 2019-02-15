<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY","ANNOTATION"})
 */
class DayOffClose extends Constraint
{
    /*
     * Any public properties become valid options for the annotation.
     * Then, use these in your validator class.
     */
    public $message = 'Le {{ value }}, le musée n\'est pas ouvert ce jour ferié' ;
}
