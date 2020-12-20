<?php

namespace App\DataFixtures;

use App\Entity\Route;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class RouteFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->provideRoutes() as [$name, $data, $search, $userIncrement]) {
            $user = $this->getReference(User::class.'_'.$userIncrement);

            $route = (new Route())
                ->setName($name)
                ->setData($data)
                ->setSearch($search)
                ->setUser($user)
            ;

            $manager->persist($route);
        }

        $manager->flush();
    }

    private function provideRoutes(): array
    {
        $junior_bike = [
            'My junior route',
            [
                "waypoint1" => "point1",
                "waypoint2" => "point2"
            ],
            [
                "profile" => "cycling-road",
                "difficulty" => "1"
            ],
            0
        ];
        $senior_bike = [
            'My senior route',
            [
                "waypoint1" => "point12",
                "waypoint2" => "point22"
            ],
            [
                "profile" => "cycling-regular",
                "difficulty" => "2"
            ],
            1
        ];

        return [$junior_bike, $senior_bike];
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class
        ];
    }
}
