<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RouteTest extends WebTestCase
{
    /** @var \Symfony\Bundle\FrameworkBundle\KernelBrowser */
    private $client;

    public function setUp(): void
    {
        $this->client = self::createClient();
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
        yield ['/', Response::HTTP_MOVED_PERMANENTLY];
        yield ['/en/', Response::HTTP_OK];
        yield ['/en/contact', Response::HTTP_OK];
        yield ['/en/login', Response::HTTP_OK];
        yield ['/en/user/registration', Response::HTTP_OK];
    }
}
