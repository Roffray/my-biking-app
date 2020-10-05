<?php

namespace App\Controller;

use App\Form\RegistrationType;
use App\User\RegistrationHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user", name="user_")
 */
class UserController extends AbstractController
{
    /** @var RegistrationHandler */
    private $registrationHandler;

    public function __construct(RegistrationHandler $registrationHandler)
    {
        $this->registrationHandler = $registrationHandler;
    }

    /**
     * @Route("/registration", name="registration", methods={"GET", "POST"})
     */
    public function register(Request $request): Response
    {
        $form = $this->createForm(RegistrationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->registrationHandler->handle($form->getData());

            $this->addFlash('info', 'Welcome to My Biking App ! Please check your emails to activate your account.');
            return $this->redirectToRoute("home");
        }

        return $this->render('user/registration/registration.html.twig', [
            'form'  => $form->createView()
        ]);
    }
}
