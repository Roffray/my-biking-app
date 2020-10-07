<?php

namespace App\Validator;

use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\InvalidOptionsException;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueUserValidator extends ConstraintValidator
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint \App\Validator\UniqueUser */

        if (!$constraint instanceof UniqueUser) {
            throw new UnexpectedTypeException($constraint, UniqueUser::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($constraint->field)) {
            throw new UnexpectedTypeException($constraint->field, 'string');
        }

        if (empty($constraint->field)) {
            throw new InvalidOptionsException(
                sprintf('The option "field" should not be empty for constraint "%s"', UniqueUser::class),
                []
            );
        }

        if (!is_string($value)) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $user = $this->userRepository->findOneBy([
            $constraint->field => $value
        ]);

        if (!$user) {
            return;
        }

        $this->context->buildViolation($constraint->message.$constraint->field)
            ->setParameter('{{ value }}', $value)
//            ->setParameter('{{ field }}', $constraint->field)
            ->addViolation();
    }
}
