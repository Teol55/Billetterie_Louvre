<?php
/**
 * Created by PhpStorm.
 * User: tof
 * Date: 15/02/2019
 * Time: 08:57
 */

namespace App\Tests\src\Validator;


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

    public function testNotTuesdayTestOk()
    {

        $this->validator->validate(new \DateTime('2019-02-20'), new NotTuesday());
        $this->assertNoViolation();

    }

}