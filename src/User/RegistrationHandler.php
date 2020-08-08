<?php

namespace App\User;

use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationHandler
{
    /** @var UserManager */
    private $userManager;
    /** @var \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface */
    private $passwordEncoder;

    public function __construct(UserManager $userManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->userManager = $userManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function handle(RegistrationData $data): User
    {
        $user = new User();

        $user
            ->setEmail($data->email)
            ->setName($data->name)
            ->setPassword($this->passwordEncoder->encodePassword($user, $data->password))
        ;

        $this->userManager->save($user);

        return $user;
    }
}
