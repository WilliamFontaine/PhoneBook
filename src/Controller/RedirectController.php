<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RedirectController extends AbstractController
{
  #[Route("/{wildcard}", name: "app_redirect", requirements: ["wildcard" => ".*"])]
  public function redirectAction(): Response
  {
    return $this->redirectToRoute('app_contacts_index');
  }
}
