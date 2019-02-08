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


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;




class VisitorTest extends WebTestCase
{

    /**
     * @dataProvider dateAgeForVisitor
     */
    public function testAge($date,$age)
    {
        $ticket = new Ticket();
        $ticket->setDateVisit(new \DateTime('2020-01-01'));
        $visitor=  new Visitor();
        $visitor->setTicket($ticket);
        $visitor->setBirthday(new \DateTime($date));


       $this->assertSame($age,$visitor->age());



    }

    public function dateAgeForVisitor()
    {
        return[['1979-01-15',40],
                ['2009-04-16',10],
                ['1978-08-01',41],
             ['2011-08-07',8],
            ['1955-02-01',64],
            ['2018-01-01',2]

        ];
    }

}