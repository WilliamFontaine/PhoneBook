<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/contacts')]
class ContactsController extends AbstractController
{
  #[Route('', name: 'app_contacts_index', methods: ['GET'])]
  public function index(): Response
  {
    return $this->render("contacts/index.html.twig", [
      'controller_name' => 'ContactsController',
    ]);
  }

  #[Route('/new', name: 'app_contacts_new', methods: ['GET'])]
  public function new(): Response
  {
    return $this->render("contacts/detail.html.twig", [
      'id' => null
    ]);
  }

  #[Route('/{id}', name: 'app_contacts_edit', methods: ['GET'])]
  public function show(string $id): Response
  {
    return $this->render("contacts/detail.html.twig", [
      'id' => $id
    ]);
  }
}
