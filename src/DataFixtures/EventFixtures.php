<?php

namespace App\DataFixtures;

use App\Entity\Event;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class EventFixtures extends BaseFixture implements DependentFixtureInterface
{
    protected function loadData()
    {
        $this->createMany(50, 'event', function(){
            return (new Event())
            ->setNom($this->faker->catchPhrase)
            ->setDescription($this->faker->realText())
            ->setDate($this->faker->dateTimeBetween('now', '+2 years'))
            ->setAuteur($this->getRandomReference('user'))
            ->setLieu($this->faker->streetAddress())
            ;
        });
        
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}

