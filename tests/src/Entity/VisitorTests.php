<?php
/**
 * Created by PhpStorm.
 * User: tof
 * Date: 04/02/2019
 * Time: 15:13
 */

namespace App\Tests\src\Entity;


use App\Entity\Visitor;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;




class VisitorTests extends WebTestCase
{

    /**
     * @dataProvider dateAgeForVisitor
     */
    public function testAge($date,$age)
    {
        $visitor=  new Visitor();

       $test=$visitor->age(new \DateTime($date) );

       $this->assertSame($age,$test);



    }

    public function dateAgeForVisitor()
    {
        return[['15-01-1979',40],
                ['16-04-2009',9],
                ['01-08-1978',40],
             ['07-08-2011',7],
            ['01-02-1955',64],
            ['01-04-2018',0]

        ];
    }

}