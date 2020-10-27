<?php

namespace App\User;

use Symfony\Component\Validator\Constraints as Assert;

class ProfileData
{
    /**
     * @Assert\NotBlank
     */
    public $name;

    /**
     * @Assert\NotBlank
     * @Assert\Email
     */
    public $email;
}
