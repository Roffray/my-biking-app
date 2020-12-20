<?php

namespace App\Tests\Api;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UsersTest extends ApiTestCase
{
    /** @var Client */
    private $client;

    public function setUp(): void
    {
        $this->client = self::createClient();
    }

    public function test_getCollection(): void
    {
        $this->client->request(Request::METHOD_GET, '/api/users');

        self::assertResponseIsSuccessful();

        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        self::assertJsonContains([
            '@context' => '/api/contexts/User',
            '@id' => '/api/users',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => 5,
            'hydra:view' => [
                '@id' => '/api/users?page=1',
                '@type' => 'hydra:PartialCollectionView',
                'hydra:first' => '/api/users?page=1',
                'hydra:last' => '/api/users?page=3',
                'hydra:next' => '/api/users?page=2',
            ],
        ]);
    }

    public function test_getUser(): void
    {
        $iri_user = $this->findIriBy(User::class, ['email' => 'junior@mybikingapp.fr']);

        $this->client->request(Request::METHOD_GET, $iri_user);

        self::assertResponseIsSuccessful();

        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        self::assertJsonContains([
            '@context' => '/api/contexts/User',
            '@id' => $iri_user,
            '@type' => 'User',
        ]);
    }

    public function test_createUser(): void
    {
        $this->client->request(Request::METHOD_POST, '/api/users', [
            'json'  => [
                'email' => 'email@mybikingapp.com',
                'name'  => 'User'
            ]
        ]);

        self::assertResponseStatusCodeSame(Response::HTTP_METHOD_NOT_ALLOWED);
    }

    public function test_updateUserNotLoggedIn(): void
    {
        $iri = $this->findIriBy(User::class, ['email' => 'junior@mybikingapp.fr']);
        $this->client->request(Request::METHOD_PATCH, $iri, [
            'json'      => [
                'email' => 'email@mybikingapp.com',
                'name'  => 'User'
            ],
            'headers'   =>[
                'Content-Type'  => 'application/merge-patch+json'
            ]
        ]);


        self::assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function test_updateWrongUserLoggedIn(): void
    {
        $userRepository = self::$container->get(UserRepository::class);

        $logged_in_user = $userRepository->findOneByEmail('junior@mybikingapp.fr');

        $wrong_user = $userRepository->findOneByEmail('senior@mybikingapp.fr');

        $this->client->getKernelBrowser()->loginUser($logged_in_user);

        $iri = $this->findIriBy(User::class, ['email' => 'senior@mybikingapp.fr']);

        $this->client->request(Request::METHOD_PATCH, $iri, [
            'json'      => [
                'email' => 'junior1@mybikingapp.fr',
                'name'  => 'User Junior 1'
            ],
            'headers'   =>[
                'Content-Type'  => 'application/merge-patch+json'
            ]
        ]);

        self::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
        self::assertEquals('User Junior', $logged_in_user->getName());
        self::assertEquals('junior@mybikingapp.fr', $logged_in_user->getEmail());
        self::assertEquals('User Senior', $wrong_user->getName());
        self::assertEquals('senior@mybikingapp.fr', $wrong_user->getEmail());
    }

    public function test_updateUserLoggedIn(): void
    {
        $userRepository = self::$container->get(UserRepository::class);

        $logged_in_user = $userRepository->findOneByEmail('junior@mybikingapp.fr');

        $this->client->getKernelBrowser()->loginUser($logged_in_user);

        $iri = $this->findIriBy(User::class, ['email' => 'junior@mybikingapp.fr']);

        $this->client->request(Request::METHOD_PATCH, $iri, [
            'json'      => [
                'email' => 'junior1@mybikingapp.fr',
                'name'  => 'User Junior 1'
            ],
            'headers'   =>[
                'Content-Type'  => 'application/merge-patch+json'
            ]
        ]);

        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertEquals('User Junior 1', $logged_in_user->getName());
        self::assertEquals('junior1@mybikingapp.fr', $logged_in_user->getEmail());
    }
}