<?php

namespace App\Contact;

use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class ContactHandler
{

    /**
     * @var Environment
     */
    private $twig;
    /**
     * @var MailerInterface
     */
    private $mailer;
    /**
     * @var string
     */
    private $adminEmail;
    /**
     * @var string
     */
    private $noReplyEmail;

    public function __construct(
        Environment $twig,
        MailerInterface $mailer,
        string $adminEmail,
        string $noReplyEmail
    )
    {
        $this->twig = $twig;
        $this->mailer = $mailer;
        $this->adminEmail = $adminEmail;
        $this->noReplyEmail = $noReplyEmail;
    }

    public function handle(ContactData $contactData): void
    {
        $email = (new Email())
            ->addTo($this->adminEmail)
            ->addFrom($contactData->email)
            ->priority(Email::PRIORITY_NORMAL)
            ->subject("Message from {$contactData->firstname}")
            ->html($this->twig->render('emails/contact.html.twig', [
                'firstname' => $contactData->firstname,
                'message'   => $contactData->message
            ]))
        ;

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            //TODO: Handle TransportExceptionInterface
        }

        $email = (new Email())
            ->addTo($contactData->email)
            ->addFrom($this->noReplyEmail)
            ->priority(Email::PRIORITY_NORMAL)
            ->subject('Message sent to MyBikingApp')
            ->html($this->twig->render('emails/contact.html.twig', [
                'firstname' => $contactData->firstname,
                'message'   => $contactData->message
            ]))
        ;


        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            //TODO: Handle TransportExceptionInterface
        }
    }
}