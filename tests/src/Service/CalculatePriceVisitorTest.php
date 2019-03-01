<?php
/**
 * Created by PhpStorm.
 * User: tof
 * Date: 04/02/2019
 * Time: 15:13
 */

namespace App\Tests\src\Entity;


use App\Entity\Ticket;
use App\Entity\Visitor;


use App\Service\CalculatePriceVisitor;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;




class CalculatePriceVisitorTest extends WebTestCase
{




    /**
     * @dataProvider dateBirthdayVisitor
     */
    public function testPrice($date,$price,$typeTicket)
    {

        $calculatePriceVisitor=new CalculatePriceVisitor();

        $visitor=new Visitor();
        $visitor->setBirthday(new \DateTime($date));
        $visitor->setReduction(false);
        $ticket = new Ticket();
        $ticket->setTypeTicket($typeTicket);
        $ticket->addVisitor($visitor);
        $ticket->setDateVisit(new \DateTime('2020-01-01'));

        $calculatePriceVisitor->visitorPrice($ticket);


       $this->assertSame($price,$visitor->getPrice());

        $this->assertSame($price,$ticket->getPrice());



    }

    public function dateBirthdayVisitor()
    {
        return[
            ['1979-01-15',16.0,'tarifJournee'],
            ['2009-04-16',8.0,'tarifJournee'],
            ['1978-08-01',16.0,'tarifJournee'],
            ['2011-08-07',8.0,'tarifJournee'],
            ['1955-02-01',12.0,'tarifJournee'],
            ['2018-01-01',0.0,'tarifJournee'],
            ['1979-01-15',8.0,'tarifDemiJournée'],
            ['2009-04-16',4.0,'tarifDemiJournée'],
            ['1978-08-01',8.0,'tarifDemiJournée'],
            ['2011-08-07',4.0,'tarifDemiJournée'],
            ['1955-02-01',6.0,'tarifDemiJournée'],
            ['2018-01-01',0.0,'tarifDemiJournée']

        ];
    }

}