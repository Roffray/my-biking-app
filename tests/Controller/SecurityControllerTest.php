<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityControllerTest extends WebTestCase
{
    public function testLogin(): void
    {
        $client = self::createClient();

        $client->request(Request::METHOD_GET, 'en/login');

        self::assertResponseIsSuccessful();

        $client->submitForm('submit', [
            'email'     => 'junior@mybikingapp.fr',
            'password'  => 'junior'
        ]);

        $client->followRedirect();
        self::assertRouteSame('home');
    }

    public function testLogin_inactiveUser(): void
    {
        $client = self::createClient();

        $client->request(Request::METHOD_GET, '/en/login');

        self::assertResponseIsSuccessful();

        $client->submitForm('submit', [
            'email'     => 'inactive@mybikingapp.fr',
            'password'  => 'inactive'
        ]);

        $client->followRedirect();

        self::assertRouteSame('app_login');
        self::assertSelectorExists('.alert-danger');
    }

    public function testLogin_emailDoesNotExist(): void
    {
        $client = self::createClient();

        $client->request(Request::METHOD_GET, '/en/login');

        self::assertResponseIsSuccessful();

        $client->submitForm('submit', [
            'email'     => 'unknown@mybikingapp.fr',
            'password'  => 'inactive'
        ]);


        $client->followRedirect();

        self::assertRouteSame('app_login');
        self::assertSelectorExists('.alert-danger');
    }

    public function testLogin_loginFromRestrictedPageAndRedirect(): void
    {
        $client = self::createClient();

        $client->request(Request::METHOD_GET, '/en/user/account');

        $client->followRedirect();

        self::assertRouteSame('app_login');

        $client->submitForm('submit', [
            'email'     => 'junior@mybikingapp.fr',
            'password'  => 'junior'
        ]);

        $client->followRedirect();

        self::assertRouteSame('user_account');
    }

    public function testLogin_afterLogin(): void
    {
        $client = self::createClient();

        $userRepository = self::$container->get(UserRepository::class);

        $logged_in_user = $userRepository->findOneByEmail('junior@mybikingapp.fr');

        $client->loginUser($logged_in_user);

        $client->request(Request::METHOD_GET, '/en/login');

        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $client->followRedirect();

        self::assertRouteSame('home');
    }
}