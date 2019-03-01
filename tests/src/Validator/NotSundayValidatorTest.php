<?php
/**
 * Created by PhpStorm.
 * User: tof
 * Date: 15/02/2019
 * Time: 08:57
 */

namespace App\Tests\src\Validator;



use App\Entity\Ticket;
use App\Validator\NotSunday;
use App\Validator\NotSundayValidator;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;


class NotSundayValidatorTest extends ConstraintValidatorTestCase
{


    protected function createValidator()
    {


        return new NotSundayValidator();

    }



    public function testNotSunday()
    {

        $this->validator->validate(new \DateTime('2019-02-26'),new NotSunday());
        $this->assertNoViolation();

    }
    public function testSunday()
    {


        $constraint= new NotSunday([
            'message'=> 'myMessage'
        ]);

        $this->validator->validate(new \DateTime('2019-03-03'),$constraint);

        $this->buildViolation($constraint->message)
                         ->assertRaised();
    }


}