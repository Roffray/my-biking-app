<?php

namespace App\Contact;

use Symfony\Component\Validator\Constraints as Assert;

class ContactData
{
    /**
     * @Assert\NotBlank()
     */
    public $firstname;

    /**
     * @Assert\NotBlank()
     */
    public $email;

    /**
     * @Assert\NotBlank()
     */
    public $message;
}