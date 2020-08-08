<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController {

  /**
   * @Route("/", name="home", methods={"GET"})
   */
  public function index(): Response
  {
    return $this->render('default/index.html.twig');
  }

  /**
   * @Route("/contact", name="contact", methods={"GET", "POST"})
   */
  public function contact(): Response
  {
    return $this->render('default/contact.html.twig');
  }

}
