<?php

namespace App\Tests\Validator;

use App\Repository\UserRepository;
use App\Validator\UniqueUser;
use App\Validator\UniqueUserValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\InvalidOptionsException;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueUserValidatorTest extends TestCase
{
    function testValidate_typeException(): void
    {
        $userRepository = $this->createMock(UserRepository::class);
        $constraint = $this->createMock(Constraint::class);

        $this->expectException(UnexpectedTypeException::class);

        $uniqueUserValidator = new UniqueUserValidator($userRepository);

        $uniqueUserValidator->validate('test', $constraint);
    }

    function testValidate_emptyValue(): void
    {
        $userRepository = $this->createMock(UserRepository::class);
        $constraint = $this->createMock(UniqueUser::class);

        $uniqueUserValidator = new UniqueUserValidator($userRepository);

        $return = $uniqueUserValidator->validate(null, $constraint);

        self::assertNull($return);
    }

    function testValidate_fieldIsNotAString(): void
    {
        $userRepository = $this->createMock(UserRepository::class);
        $constraint = new UniqueUser([
            'field' => ['test']
        ]);

        $this->expectException(UnexpectedTypeException::class);

        $uniqueUserValidator = new UniqueUserValidator($userRepository);

        $uniqueUserValidator->validate('test', $constraint);
    }

    function testValidate_fieldIsempty(): void
    {
        $userRepository = $this->createMock(UserRepository::class);
        $constraint = new UniqueUser([
            'field' => ''
        ]);

        $this->expectException(InvalidOptionsException::class);

        $uniqueUserValidator = new UniqueUserValidator($userRepository);

        $uniqueUserValidator->validate('test', $constraint);
    }

    function testValidate_valueIsNotAString(): void
    {
        $userRepository = $this->createMock(UserRepository::class);
        $constraint = new UniqueUser([
            'field' => 'test'
        ]);

        $this->expectException(UnexpectedTypeException::class);

        $uniqueUserValidator = new UniqueUserValidator($userRepository);

        $uniqueUserValidator->validate(['test'], $constraint);
    }
}