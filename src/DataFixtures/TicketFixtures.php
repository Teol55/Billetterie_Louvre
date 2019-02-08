<?php

namespace App\DataFixtures;


use App\Entity\Ticket;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;


class TicketFixtures extends BaseFixtures implements  DependentFixtureInterface
{

    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(1000, 'main_ticket', function($i) use ($manager) {

            $ticket=new Ticket();



            $ticket->setDateVisit($this->faker->dateTimeBetween('1 days', '360 days'));
            $ticket->setTypeTicket($this->faker->randomKey(['Journée','Demi-journée']));
            $ticket->setCreatedAt(new \DateTime());
            $ticket->setPrice(45);



            $ticket->setCustomer($this->getRandomReference('main_customer'));

            $ticket->setReference($i);

            return $ticket;

        });
        $manager->flush();
    }
    public function getDependencies()
    {
        return [CustomerFixtures::class,
        ];
    }
}
