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

class NotTuesdayValidatorTest extends ValidatorTestAbstract
{

    /**
     * Retourne une instance du validateur à tester.
     *
     * @return ConstraintValidator
     */
    protected function getValidatorInstance()
    {
        return new NotTuesdayValidator();
    }

    public function NotTuesdayTestOk()
    {
        $notTuesdayConstraint=New NotTuesday();
        $notTuesdayValidator=$this->initValidator();

       $this->assertTrue($notTuesdayValidator->validate(new \DateTime('2019-02-19'),$notTuesdayConstraint));
       $this->assertSame(1,1);
    }
    public function NotTuesdayTestKO()
    {
        $notTuesdayConstraint = New NotTuesday();
        $notTuesdayValidator = $this->initValidator($notTuesdayConstraint->message);

        $this->assertNotTrue($notTuesdayValidator->validate(new \DateTime('2019-02-16'),$notTuesdayConstraint->message));
    }
}