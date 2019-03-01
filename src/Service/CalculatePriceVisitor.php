<?php
/**
 * Created by PhpStorm.
 * User: tof
 * Date: 10/02/2019
 * Time: 11:39
 */

namespace App\Service;


use App\Entity\Ticket;
use App\Entity\Visitor;

class CalculatePriceVisitor
{


    const COEFF_FULL_DAY = 1;
    const COEFF_HALF_DAY = 0.5;

    public function visitorPrice(Ticket $ticket)
    {
        $priceTicket = 0;

        $visitors = $ticket->getVisitors();


        $coeff = ($ticket->getTypeTicket() == Visitor::VISIT_FULL_DAY) ? self::COEFF_FULL_DAY : self::COEFF_HALF_DAY;

        foreach ($visitors as $visitor) {
            $age = $visitor->age();


            if($age < 4){
                $visitor->setPrice(0);
            }elseif ($age < 12) {
                $visitor->setPrice(8 * $coeff);
            }elseif ($visitor->getReduction() === true && Visitor::VISIT_FULL_DAY){
             $visitor->setPrice(10);
            }elseif($age < 60){
                $visitor->setPrice(16 * $coeff);

            }else{
                $visitor->setPrice(12 * $coeff);
            }



            $priceTicket += $visitor->getPrice();
        }
        $ticket->setPrice($priceTicket);
    }
}