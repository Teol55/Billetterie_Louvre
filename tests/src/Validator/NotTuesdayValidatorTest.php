<?php
/**
 * Created by PhpStorm.
 * User: tof
 * Date: 15/02/2019
 * Time: 08:57
 */

namespace App\Tests\src\Validator;

use Symfony\Bridge\PhpUnit\DnsMock;
use App\Validator\NotTuesday;
use App\Validator\NotTuesdayValidator;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;


class NotTuesdayValidatorTest extends ConstraintValidatorTestCase
{


    protected function createValidator()
    {
        return new NotTuesdayValidator();
    }

    public function NotTuesdayTestOk()
    {

        $this->validator->validate('2019-02-19', new NotTuesday());
        $this->assertNoViolation();
    }

}