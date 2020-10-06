<?php

namespace App\Controller;

use App\Form\RegistrationType;
use App\User\RegistrationHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/user", name="user_")
 */
class UserController extends AbstractController
{
    /** @var RegistrationHandler */
    private $registrationHandler;
    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(RegistrationHandler $registrationHandler, TranslatorInterface $translator)
    {
        $this->registrationHandler = $registrationHandler;
        $this->translator = $translator;
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

            $this->addFlash('info', $this->translator->trans('registration_success', ['app_name' => $this->getParameter('app.name')]));

            return $this->redirectToRoute("home");
        }

        return $this->render('user/registration/registration.html.twig', [
            'form'  => $form->createView()
        ]);
    }
}
