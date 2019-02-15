<?php

namespace App\Validator;

use App\Entity\Ticket;
use App\Repository\VisitorRepository;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ToManyVisitorValidator extends ConstraintValidator
{
    /**
     * @var VisitorRepository
     */
    private $repository;
    Private $date;


    /**
     * ToManyVisitorValidator constructor.
     * @param VisitorRepository $repository
     */
    public function __construct(VisitorRepository $repository)
    {

        $this->repository = $repository;

    }

    public function validate($object, Constraint $constraint)
    {
        if(!$object instanceof Ticket){
            throw new \LogicException();
        }

        /* @var $constraint App\Validator\ToManyVisitor */
            $numberVistor=$this->repository->countByDateVisit($object->getDateVisit());
            if($numberVistor + $object->getNumberPlace() > 1000) {
                $this->context->buildViolation($constraint->message)
                    ->setParameter('{{ value }}', $numberVistor)
                    ->addViolation();
            }
    }
}
