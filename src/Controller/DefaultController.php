<?php

namespace App\Controller;

use App\Contact\ContactHandler;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class DefaultController extends AbstractController {

    /**
     * @var ContactHandler
     */
    private $contactHandler;

    public function __construct(ContactHandler $contactHandler)
    {
        $this->contactHandler = $contactHandler;
    }

    /**
     * @Route("/", name="home", methods={"GET"})
     */
    public function index(Request $request): Response
    {
        return $this->render('default/index.html.twig');
    }

    /**
     * @Route("/contact", name="contact", methods={"GET", "POST"})
     */
    public function contact(Request $request, TranslatorInterface $translator): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->contactHandler->handle($form->getData());

            $this->addFlash('success',
                $translator->trans('email_contact_sent_success'));

            return $this->redirectToRoute('home');
        }

        return $this->render('default/contact.html.twig', [
            'contact_form'  => $form->createView()
        ]);
    }

}
