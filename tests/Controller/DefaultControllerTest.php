<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = self::createClient();

        $client->request(Request::METHOD_GET, '/en/');
        self::assertResponseIsSuccessful();
    }

    public function testContact(): void
    {
        $client = self::createClient();

        $client->request(Request::METHOD_GET, '/en/contact');
        self::assertResponseIsSuccessful();

        $client->enableProfiler();

        $client->submitForm('contact[submit]', [
            'contact[firstname]'    => 'Test',
            'contact[email]'        => 'test@test.yz',
            'contact[message]'      => 'Message'
        ]);

        if ($profile = $client->getProfile()) {
            self::assertLessThan(
                500,
                $client->getProfile()->getCollector('time')->getDuration()
            );
        }

        $client->followRedirect();
        self::assertSelectorExists('.alert-success');
    }
}