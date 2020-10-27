<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends WebTestCase
{
    /** @var KernelBrowser */
    private $client;

    public function setUp(): void
    {
        $this->client = self::createClient();
    }

    public function testRegister():void
    {
        $this->client->request(Request::METHOD_GET, 'en/user/registration');

        self::assertResponseIsSuccessful();

        $this->client->enableProfiler();

        $this->client->submitForm('registration[submit]', [
            'registration[email]'               => 'test@bikingapp.com',
            'registration[name]'                => 'usertest',
            'registration[password][first]'     => 'passwordtest',
            'registration[password][second]'    => 'passwordtest',
        ]);

        if ($profile = $this->client->getProfile()) {
            self::assertLessThan(
                600,
                $profile->getCollector('time')->getDuration()
            );
        }

        $this->client->followRedirect();

        self::assertRouteSame('home');
        self::assertSelectorExists('.alert-info');
    }

    public function testRegister_emailAlreadyExists(): void
    {
        $this->client->request(Request::METHOD_GET, '/en/user/registration');

        self::assertResponseIsSuccessful();

        $this->client->submitForm('registration[submit]', [
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
        $this->client->request(Request::METHOD_GET, '/en/user/registration');

        self::assertResponseIsSuccessful();

        $this->client->submitForm('registration[submit]', [
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
        $userRepository = self::$container->get(UserRepository::class);

        $logged_in_user = $userRepository->findOneByEmail('junior@mybikingapp.fr');

        $this->client->loginUser($logged_in_user);

        $this->client->request(Request::METHOD_GET, '/en/user/registration');

        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $this->client->followRedirect();

        self::assertRouteSame('home');
    }

    public function testAccount_anonymousUser(): void
    {
        $this->client->request(Request::METHOD_GET, '/en/user/account');

        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $this->client->followRedirect();

        self::assertRouteSame('app_login');
    }
}