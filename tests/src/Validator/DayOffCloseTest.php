<?php
/**
 * Created by PhpStorm.
 * User: tof
 * Date: 15/02/2019
 * Time: 08:57
 */

namespace App\Tests\src\Validator;





use App\Validator\DayOffClose;
use App\Validator\DayOffCloseValidator;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;


class DayOffCloseTest extends ConstraintValidatorTestCase
{


    protected function createValidator()
    {


        return new DayOffCloseValidator();

    }


    /**
     * @dataProvider dateVisitValide
     */
    public function testNotDayClose($date)
    {

        $this->validator->validate(new \DateTime($date),new DayOffClose());
        $this->assertNoViolation();

    }
    /**
     * @dataProvider dateVisitClose
     */
    public function testDayClose($date)
    {
        $constraint= new DayOffClose([
            'message'=>'myMessage'
        ]);

        $this->validator->validate(new \DateTime($date),$constraint);

        $this->buildViolation($constraint->message)
            ->setParameter('{{ value }}',date_format(new \DateTime($date),'d-m-Y'))
            ->assertRaised();
    }

    public function dateVisitClose()
    {
        return[['2020-05-01'],
            ['2019-12-25'],
            ['2019-11-01'],
            ['2019-05-01'],

        ];
    }
    public function dateVisitValide()
    {
        return[['2020-01-03'],
        ['2019-05-07'],
            ['2019-07-13'],
            ['2019-08-14'],
            ['2020-11-02'],
            ['2019-11-12']

        ];
    }
}