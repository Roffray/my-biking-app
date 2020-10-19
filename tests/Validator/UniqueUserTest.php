<?php

namespace App\Tests\Validator;

use App\Validator\UniqueUser;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Exception\MissingOptionsException;

class UniqueUserTest extends TestCase
{
    public function testGetDefaultOption(): void
    {
        $uniqueUser = new UniqueUser([
            'field' => 'email'
        ]);

        self::assertEquals('field', $uniqueUser->getDefaultOption());
    }

    public function testGetRequiredOptions(): void
    {
        $uniqueUser = new UniqueUser([
            'field' => 'name'
        ]);

        self::assertEquals(['field'], $uniqueUser->getRequiredOptions());
    }

    public function test_noFieldOption(): void
    {
        $this->expectException(MissingOptionsException::class);

        new UniqueUser();
    }
}