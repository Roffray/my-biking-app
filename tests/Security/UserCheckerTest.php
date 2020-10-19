<?php

namespace App\Tests\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Security\UserChecker;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Translation\Translator;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserCheckerTest extends TestCase
{
    /**
     * @doesNotPerformAssertions
     */
    public function testCheckPreAuth(): void
    {
        $translator = new Translator('en');
        $userChecker = new UserChecker($translator);

        $user = (new User())
            ->setEmail('test@email.com')
            ->setName('Test')
            ->setIsActive(true)
        ;

        $userChecker->checkPreAuth($user);
    }

    public function testCheckPreAuth_inactiveUserException(): void
    {
        $translator = new Translator('en');
        $userChecker = new UserChecker($translator);

        $user = (new User())
            ->setEmail('test@email.com')
            ->setName('Test')
            ->setIsActive(false)
        ;

        $this->expectException(CustomUserMessageAccountStatusException::class);
        $this->expectExceptionMessage($translator->trans('user.message.inactive'));

        $userChecker->checkPreAuth($user);
    }

    public function testCheckPreAuth_notInstanceOfUser(): void
    {
        $translator = $this->createMock(TranslatorInterface::class);
        $user = $this->createMock(UserInterface::class);

        $userChecker = new UserChecker($translator);

        $return = $userChecker->checkPreAuth($user);

        self::assertNull($return);
    }
}