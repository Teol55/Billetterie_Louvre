<?php

namespace App\DataFixtures;


use App\Entity\Ticket;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;


class TicketFixtures extends BaseFixtures implements  DependentFixtureInterface
{

    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(1000, 'main_order', function($i) use ($manager) {

            $order=new Ticket();



            $order->setDateVisit($this->faker->dateTimeBetween('1 days', '360 days'));
            $order->setTypeTicket($this->faker->randomKey(['Journée','Demi-journée']));
            $order->setCreatedAt(new \DateTime());
            $order->setPrice(45);



            $order->setCustomer($this->getRandomReference('main_customer'));

            $order->setReference($i);

            return $order;

        });
        $manager->flush();
    }
    public function getDependencies()
    {
        return [CustomerFixtures::class,
        ];
    }
}
