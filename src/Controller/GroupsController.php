<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/groups')]
class GroupsController extends AbstractController
{
    #[Route('', name: 'app_groups')]
    public function index(): Response
    {
        return $this->render('groups/index.html.twig');
    }

    #[Route('/{id}', name: 'app_groups_edit', methods: ['GET'])]
    public function show(string $id): Response
    {
        return $this->render('groups/detail.html.twig', [
            'id' => $id
        ]);
    }
}
