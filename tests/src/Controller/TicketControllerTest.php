<?php
/**
 * Created by PhpStorm.
 * User: tof
 * Date: 25/02/2019
 * Time: 10:19
 */

namespace App\Tests\src\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TicketControllerTest extends WebTestCase
{
    public function testHomepageISup()
    {
        $clients = static::createClient();
        $clients->request('GET', '/');

        $this->assertSame(200, $clients->getResponse()->getStatusCode());

    }

    public function testAddNewVisit()
    {
        $clients = static::createClient();
        $crawler = $clients->request('GET', '/');
        $form = $crawler->selectButton('Valider')->form();
        $form['order_form[dateVisit]'] = '2019-03-28';
        $form['order_form[typeTicket]'] = 'tarifJournee';
        $form['order_form[numberPlace]'] = '3';

        $clients->submit($form);
        $crawler=$clients->followRedirect();
        $this->assertSame(1,$crawler->filter('div.quote-space.pb-2.pt-2.px-5')->count());


    }
    public function testContact()

    {
        $clients = static::createClient();
        $crawler = $clients->request('GET', '/');
        $link=$crawler->selectLink('contact')->link();
        $crawler=$clients->click($link);


        $form = $crawler->selectButton('Envoyer')->form();
        $form['contact_form[nameContact]'] = 'MARTIN';
        $form['contact_form[emailContact]'] = 'martin@lol.fr';
        $form['contact_form[messageContact]'] = 'test fonctionnel';

        $clients->submit($form);
        $crawler=$clients->followRedirect();
        $this->assertSame(1,$crawler->filter('div.alert.alert-success')->count());



    }

}