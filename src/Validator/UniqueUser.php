<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueUser extends Constraint
{
    /*
     * Any public properties become valid options for the annotation.
     * Then, use these in your validator class.
     */
    public $message = 'registration.used.';

    public $field = null;

    public function getDefaultOption()
    {
        return 'field';
    }

    public function getRequiredOptions()
    {
        return ['field'];
    }
}
