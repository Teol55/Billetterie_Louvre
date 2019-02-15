<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ToManyVisitor extends Constraint
{
    /*
     * Any public properties become valid options for the annotation.
     * Then, use these in your validator class.
     */
    public $message = 'Nous sommes désolé,il n\'y a plus de places';

    public function getTargets()
    {
        return Constraint::CLASS_CONSTRAINT;
    }


}
