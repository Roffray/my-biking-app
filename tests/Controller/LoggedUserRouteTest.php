<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LoggedUserRouteTest extends WebTestCase
{
    /** @var \Symfony\Bundle\FrameworkBundle\KernelBrowser */
    private $client;

    public function setUp(): void
    {
        $this->client = self::createClient();

        $userRepository = self::$container->get(UserRepository::class);

        $logged_in_user = $userRepository->findOneByEmail('junior@mybikingapp.fr');

        $this->client->loginUser($logged_in_user);
    }

    /**
     * @param string $path
     * @param int $statusCode
     *
     * @dataProvider routeProvider
     */
    public function testRoute(string $path, int $statusCode): void
    {
        $this->client->request(Request::METHOD_GET, $path);

        self::assertResponseStatusCodeSame($statusCode);
    }

    public function routeProvider(): iterable
    {
        yield ['/en/user/account', Response::HTTP_OK];
    }
}
