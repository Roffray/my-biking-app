<?php

namespace App\DataFixtures;

use App\Entity\Bike;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class BikeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        foreach ($this->provideBikes() as [$name, $userIncrement]) {
            $user = $this->getReference(User::class.'_'.$userIncrement);

            $bike = (new Bike())
                ->setName($name)
                ->setUser($user)
            ;

            $manager->persist($bike);
        }

        $manager->flush();
    }

    private function provideBikes(): array
    {
        $junior_bike = [
            'My brand new BTWIN 520',
            0
        ];
        $senior_bike = [
            'BTWIN 540 custom',
            1
        ];
        $inactive_bike = [
            'My road bike',
            4
        ];

        return [$junior_bike, $senior_bike, $inactive_bike];
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class
        ];
    }
}
