<?php
/**
 * Created by PhpStorm.
 * User: tof
 * Date: 15/02/2019
 * Time: 08:57
 */

namespace App\Tests\src\Validator;




use App\Validator\NotBefore;
use App\Validator\NotBeforeValidator;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;


class NotBeforeValidatorTest extends ConstraintValidatorTestCase
{


    protected function createValidator()
    {


        return new NotBeforeValidator();

    }



    public function testNotBefore()
    {

        $this->validator->validate(new \DateTime('2019-03-03'),new NotBefore());
        $this->assertNoViolation();

    }
    public function testBeforeToDay()
    {


        $constraint= new NotBefore([
            'message'=> 'myMessage'
        ]);

        $this->validator->validate(new \DateTime('2019-02-01'),$constraint);

        $this->buildViolation($constraint->message)
                         ->assertRaised();
    }


}