<?php

namespace App\DataFixtures;

use App\Entity\Visitor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class VisitorFixtures extends BaseFixtures implements DependentFixtureInterface
{


    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(10000, 'main_visitor', function($i) {
            $visitor= new Visitor();
            $visitor->setName($this->faker->name)->setFirstName($this->faker->firstName)->setCountry($this->faker->country)
            ->setBirthday($this->faker->dateTimeBetween('-70 years ', '- 2 years'));

            $age=$visitor->age($visitor->getBirthday());

            if($age>'4' && $age <= '12')
            {
                $visitor->setPrice(8);
            }
            elseif ($age>'12' && $age <'60') {
                $visitor->setPrice(16);
            }
            elseif ( $age >='60')
            {
            $visitor->setPrice(12);
            }
            else $visitor->setPrice(0);

            $visitor->setOrder($this->getRandomReference('main_order'));

            return $visitor;
        });
        $manager->flush();
    }
    public function getDependencies()
    {
        return [CustomerFixtures::class,
            TicketFixtures::class,];
    }
}
