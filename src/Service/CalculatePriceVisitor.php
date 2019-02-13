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
    private $priceTicket =0;

    public function visitorPrice(Ticket $ticket)
        {
            $visitors=$ticket->getVisitors();

            if($ticket->getTypeTicket()=='tarifJournee'){
                    $typetarif= 1;
            }
            else $typetarif= 0.5;

            foreach ($visitors as $visitor)
            {
                $age=$visitor->age();
                if($visitor->getReduction()==='true'){


                }

                if($age>'4' && $age <= '12')
                {
                    $visitor->setPrice(8*$typetarif);
                }


                elseif ($age>'12' && $age <'60') {

                    if($visitor->getReduction()=== false)
                    {$visitor->setPrice(16*$typetarif);}
                    else $visitor->setPrice(10);
                }
                elseif ( $age >='60')
                {
                    if($visitor->getReduction()=== false)
                    {$visitor->setPrice(12*$typetarif);}
                    else $visitor->setPrice(10);
                }
                else $visitor->setPrice(0);

                $this->priceTicket+=$visitor->getPrice();
            }
            $ticket->setPrice($this->priceTicket);
        }
}