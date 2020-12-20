<?php

namespace App\Tests\Api;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client;
use App\Entity\Route;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RouteTest extends ApiTestCase
{
    /** @var Client */
    private $client;

    public function setUp(): void
    {
        $this->client = self::createClient();
    }

    public function test_getCollection(): void
    {
        $this->client->request(Request::METHOD_GET, '/api/routes');

        self::assertResponseIsSuccessful();

        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        self::assertJsonContains([
            '@context' => '/api/contexts/Route',
            '@id' => '/api/routes',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => 2,
        ]);
    }

    public function test_getRoute(): void
    {
        $iri_route = $this->findIriBy(Route::class, ['name' => 'My junior route']);

        $this->client->request(Request::METHOD_GET, $iri_route);

        self::assertResponseIsSuccessful();

        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        self::assertJsonContains([
            '@context' => '/api/contexts/Route',
            '@id' => $iri_route,
            '@type' => 'Route',
        ]);
    }

    public function test_createRouteUserNotLoggedIn(): void
    {
        $this->client->request(Request::METHOD_POST, '/api/routes', [
            'json'  => [
                'name'      => 'My Route',
                'data'      => ['data'],
                'search'    => ['search']
            ]
        ]);

        self::assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function test_createRouteWrongUserLoggedIn(): void
    {
        $userRepository = self::$container->get(UserRepository::class);

        $logged_in_user = $userRepository->findOneByEmail('junior@mybikingapp.fr');

        $this->client->getKernelBrowser()->loginUser($logged_in_user);

        $iri = $this->findIriBy(User::class, ['email' => 'senior@mybikingapp.fr']);

        $this->client->request(Request::METHOD_POST, '/api/routes', [
            'json'  => [
                'name'      => 'My Route',
                'data'      => ['data'],
                'search'    => ['search'],
                'user'      => $iri
            ]
        ]);

        self::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function test_createRouteUserLoggedIn(): void
    {
        $userRepository = self::$container->get(UserRepository::class);

        $logged_in_user = $userRepository->findOneByEmail('junior@mybikingapp.fr');

        $this->client->getKernelBrowser()->loginUser($logged_in_user);

        $iri = $this->findIriBy(User::class, ['email' => 'junior@mybikingapp.fr']);

        $this->client->request(Request::METHOD_POST, '/api/routes', [
            'json'  => [
                'name'      => 'My Route',
                'data'      => ['data'],
                'search'    => ['search'],
                'user'      => $iri
            ]
        ]);

        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);
    }

    public function test_editRoute(): void
    {
        $iri_route = $this->findIriBy(Route::class, ['name' => 'My junior route']);

        $this->client->request(Request::METHOD_PATCH, $iri_route, [
            'json'  => [
                'name'      => 'My junior Route 2',
                'data'      => ['data'],
                'search'    => ['search']
            ]
        ]);

        self::assertResponseStatusCodeSame(Response::HTTP_METHOD_NOT_ALLOWED);
    }

    public function test_deleteUserNotLoggedIn(): void
    {
        $iri_route = $this->findIriBy(Route::class, ['name' => 'My senior route']);

        $this->client->request(Request::METHOD_DELETE, $iri_route);

        self::assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function test_deleteWrongRouteUserLoggedIn(): void
    {
        $userRepository = self::$container->get(UserRepository::class);

        $logged_in_user = $userRepository->findOneByEmail('junior@mybikingapp.fr');

        $this->client->getKernelBrowser()->loginUser($logged_in_user);

        $iri_route = $this->findIriBy(Route::class, ['name' => 'My senior route']);

        $this->client->request(Request::METHOD_DELETE, $iri_route);

        self::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function test_deleteRouteUserLoggedIn(): void
    {
        $userRepository = self::$container->get(UserRepository::class);

        $logged_in_user = $userRepository->findOneByEmail('junior@mybikingapp.fr');

        $this->client->getKernelBrowser()->loginUser($logged_in_user);

        $iri_route = $this->findIriBy(Route::class, ['name' => 'My junior route']);

        $this->client->request(Request::METHOD_DELETE, $iri_route);

        self::assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }
}