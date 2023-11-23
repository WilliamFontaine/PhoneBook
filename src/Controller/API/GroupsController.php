<?php

namespace App\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/groups')]
class GroupsController extends AbstractController
{
    /*
        TODO: Faire le CRUD pour les groupes
        TODO: Les groupes sont crés lorsqu'un contact y est ajouté (créé à la création/moification du contact)
        TODO: possibilité de modifier le groupe dans une page dédiée mais pas possible d'un créer un nouveau)
        TODO: Ils cessent d'exister lorsqu'ils ne contiennent plus de contacts
    */

}
