<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class UserControllerTest extends WebTestCase
{
    public function testRegister():void
    {
        $client = self::createClient();

        $client->request(Request::METHOD_GET, 'en/user/registration');

        self::assertResponseIsSuccessful();

        $client->enableProfiler();

        $client->submitForm('registration[submit]', [
            'registration[email]'               => 'test@bikingapp.com',
            'registration[name]'                => 'usertest',
            'registration[password][first]'     => 'passwordtest',
            'registration[password][second]'    => 'passwordtest',
        ]);

        if ($profile = $client->getProfile()) {
            self::assertLessThan(
                600,
                $profile->getCollector('time')->getDuration()
            );
        }

        $client->followRedirect();

        self::assertRouteSame('home');
        self::assertSelectorExists('.alert-info');
    }

    public function testRegister_emailAlreadyExists(): void
    {
        $client = self::createClient();

        $client->request(Request::METHOD_GET, '/en/user/registration');

        self::assertResponseIsSuccessful();

        $client->submitForm('registration[submit]', [
            'registration[email]'               => 'junior@mybikingapp.fr',
            'registration[name]'                => 'usertest',
            'registration[password][first]'     => 'passwordtest',
            'registration[password][second]'    => 'passwordtest',
        ]);

        self::assertRouteSame('user_registration');
        self::assertSelectorExists('.form-error-message');
    }

    public function testRegister_nameAlreadyExists(): void
    {
        $client = self::createClient();

        $client->request(Request::METHOD_GET, '/en/user/registration');

        self::assertResponseIsSuccessful();

        $client->submitForm('registration[submit]', [
            'registration[email]'               => 'testunique@mybikingapp.fr',
            'registration[name]'                => 'User junior',
            'registration[password][first]'     => 'passwordtest',
            'registration[password][second]'    => 'passwordtest',
        ]);

        self::assertRouteSame('user_registration');
        self::assertSelectorExists('.form-error-message');
    }

    public function testRegister_afterLogin(): void
    {
        $client = self::createClient();

        $userRepository = self::$container->get(UserRepository::class);

        $logged_in_user = $userRepository->findOneByEmail('junior@mybikingapp.fr');

        $client->loginUser($logged_in_user);

        $client->request(Request::METHOD_GET, '/en/user/registration');

        self::assertResponseStatusCodeSame(302);

        $client->followRedirect();

        self::assertRouteSame('home');
    }
}