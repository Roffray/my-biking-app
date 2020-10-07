<?php

namespace App\User;

use App\Validator\UniqueUser;
use Symfony\Component\Validator\Constraints as Assert;

class RegistrationData
{
    /**
     * @Assert\NotBlank
     * @UniqueUser("name")
     */
    public $name;

    /**
     * @Assert\NotBlank
     * @Assert\Email
     * @UniqueUser("email")
     */
    public $email;

    /**
     * @Assert\NotBlank
     * @Assert\Length(min=4)
     */
    public $password;
}
