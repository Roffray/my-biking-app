<?php

namespace App\Tests\Contact;

use App\Contact\ContactData;
use App\Contact\ContactHandler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mailer\MailerInterface;
use Twig\Environment;

class ContactHandlerTest extends TestCase
{
    public function testHandle(): void
    {
        $twig = $this->createMock(Environment::class);
        $mailer = $this->createMock(MailerInterface::class);
        $mailer->expects(self::exactly(2))->method('send');

        $contactHandler = new ContactHandler(
            $twig, $mailer, 'roffray@localhost', 'roffray@localhost'
        );

        $data = $this->createMock(ContactData::class);
        $data->firstname = 'Test';
        $data->email = 'test@somewhere';
        $data->firstname = 'Test message';

        $contactHandler->handle($data);
    }

    //TODO: test the TransportExceptionInterface error
}