<?php
/**
 * Created by PhpStorm.
 * User: tof
 * Date: 15/02/2019
 * Time: 08:50
 */

namespace App\Tests\src\Validator;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Context\ExecutionContext;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilder;





    /**
     * Class ValidatorTestAbstract.
     */
abstract class ValidatorTestAbstract extends WebTestCase
{
    /**
     * Retourne une instance du validateur à tester.
     *
     * @return ConstraintValidator
     */
    abstract protected function getValidatorInstance();

    /**
     * Initialise le validateur.
     *
     * @param string|null $expectedMessage le message de violation attendu (null si on attend que la valeur soit
     *                                     validée)
     *
     * @return ConstraintValidator
     */
    protected function initValidator($expectedMessage = null)
    {
        $builder = $this->getMockBuilder(ConstraintViolationBuilder::class)
            ->disableOriginalConstructor()
            ->setMethods(['addViolation'])
            ->getMock();

        $context = $this->getMockBuilder(ExecutionContext::class)
            ->disableOriginalConstructor()
            ->setMethods(['buildViolation'])
            ->getMock();

        if ($expectedMessage) {
            $builder->expects($this->once())->method('addViolation');

            $context->expects($this->once())
                ->method('buildViolation')
                ->with($this->equalTo($expectedMessage))
                ->will($this->returnValue($builder));
        } else {
            $context->expects($this->never())->method('buildViolation');
        }

        $validator = $this->getValidatorInstance();
        /* @var ExecutionContext $context */
        $validator->initialize($context);

        return $validator;
    }


}