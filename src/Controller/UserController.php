<?php

namespace App\Controller;

use App\Form\RegistrationType;
use App\Security\LoginFormAuthenticator;
use App\User\RegistrationHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/user", name="user_")
 */
class UserController extends BaseController
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
     * @param Request $request
     * @return Response
     */
    public function register(Request $request, GuardAuthenticatorHandler $guardAuthenticatorHandler, LoginFormAuthenticator $authenticator): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }

        $form = $this->createForm(RegistrationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->registrationHandler->handle($form->getData());

            $this->addFlash('info', $this->translator->trans('registration_success', ['app_name' => $this->getParameter('app.name')]));

            return $guardAuthenticatorHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main'
            );
        }

        return $this->render('user/registration/registration.html.twig', [
            'form'  => $form->createView()
        ]);
    }
}
