<?php
/**
 * Created by PhpStorm.
 * User: tof
 * Date: 15/02/2019
 * Time: 08:57
 */

namespace App\Tests\src\Validator;



use App\Entity\Ticket;
use App\Validator\ToManyVisitor;
use App\Validator\ToManyVisitorValidator;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;


class ToManyVisitorValidatorTest extends ConstraintValidatorTestCase
{


    protected function createValidator()
    {
        $repository=$this->getMockBuilder('App\Repository\VisitorRepository')
            ->disableOriginalConstructor()
            ->setMethods(['countByDateVisit'])
            ->getMock();
        $repository->method('countByDateVisit')->willReturn(990);

        return new ToManyVisitorValidator($repository);

    }



    public function testNotToManyVisitor()
    {
        $ticket=new Ticket();
        $ticket->setNumberPlace(5);


        $this->validator->validate( $ticket, new ToManyVisitor());
        $this->assertNoViolation();

    }
    public function testToManyVisitor()
    {

        $ticket=new Ticket();
        $ticket->setNumberPlace(11);
        $constraint= new ToManyVisitor([
            'message'=> 'myMessage'
        ]);

        $this->validator->validate($ticket,$constraint);

        $this->buildViolation($constraint->message)
            ->setParameter('{{ value }}',990)
             ->assertRaised();
    }


}