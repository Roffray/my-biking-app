<?php

namespace App\DataFixtures;

use App\Entity\ApiToken;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager): void
    {
        $i = 0;

        foreach ($this->provideUsers() as [$name, $email, $role, $password, $isActive]) {
            $user = new User();

            $user
                ->setEmail($email)
                ->setName($name)
                ->setRoles([$role])
                ->setPassword($this->passwordEncoder->encodePassword(
                    $user,
                    $password
                ))
                ->setisActive($isActive)
            ;

            $apiToken = new ApiToken(
                bin2hex(random_bytes(60)),
                new \DateTime('+1 week'),
                $user
            );

            $manager->persist($apiToken);
            $manager->persist($user);

            $this->addReference(User::class.'_'.$i++, $user);
        }

        $manager->flush();
    }

    /**
     * @throws Exception Datetime Exception
     */
    private function provideUsers(): array
    {
        $junior = [
            'User Junior', 'junior@mybikingapp.fr', 'ROLE_USER', 'junior', true,
        ];

        $senior = [
            'User Senior', 'senior@mybikingapp.fr', 'ROLE_USER', 'senior', true,
        ];

        $admin = [
            'Admin', 'admin@mybikingapp.fr', 'ROLE_ADMIN', 'admin', true,
        ];

        $superAdmin = [
            'Super Admin', 'superadmin@mybikingapp.fr', 'ROLE_SUPER_ADMIN', 'superadmin', true,
        ];

        $inactive = [
            'User inactive', 'inactive@mybikingapp.fr', 'ROLE_USER', 'inactive', false,
        ];

        return [$junior, $senior, $admin, $superAdmin, $inactive];
    }
}
