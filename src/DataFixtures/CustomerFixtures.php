<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CustomerFixtures extends BaseFixtures
{


    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(1000, 'main_customer', function($i) {
            $customer= new Customer();
            $customer->setAdresseEmail($this->faker->email);


            return $customer;
        });
        $manager->flush();
    }
}
