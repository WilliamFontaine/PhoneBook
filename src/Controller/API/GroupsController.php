<?php

namespace App\Controller\API;

use App\Entity\Groups;
use App\Exception\InvalidUuidException;
use App\Repository\ContactRepository;
use App\Repository\GroupsRepository;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/groups')]
class GroupsController extends AbstractController
{
    #[Route('', name: 'api_groups_index', methods: ['GET'])]
    public function index(
        GroupsRepository    $groupsRepository,
        SerializerInterface $serializer
    ): JsonResponse
    {
        $groups = $groupsRepository->findAll();

        return new JsonResponse($serializer->serialize($groups, 'json'), Response::HTTP_OK, [], true);
    }

    #[Route('/{id}', name: 'api_groups_get', methods: ['GET'])]
    public function get(
        GroupsRepository    $groupsRepository,
        SerializerInterface $serializer,
        string              $id
    ): JsonResponse
    {
        $group = $groupsRepository->find($id);

        if (!$group) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($serializer->serialize($group, 'json'), Response::HTTP_OK, [], true);
    }

    #[Route('/{id}', name: 'api_groups_update', methods: ['PUT'])]
    public function update(
        Request                $request,
        GroupsRepository       $groupsRepository,
        SerializerInterface    $serializer,
        ValidatorInterface     $validator,
        EntityManagerInterface $em,
        string                 $id
    ): JsonResponse
    {
        $data = $request->getContent();

        $updateGroup = $serializer->deserialize($data, Groups::class, 'json');

        $group = $groupsRepository->find($id);

        $existingNameGroup = $groupsRepository->findOneBy(['name' => $updateGroup->getName()]);
        if ($existingNameGroup && $existingNameGroup !== $group) {
            return new JsonResponse([['property_path' => 'name', 'message' => 'unique']], Response::HTTP_BAD_REQUEST);
        }

        $group->setName($updateGroup->getName());


        $violations = $validator->validate($group);

        if ($violations->count() > 0) {
            return new JsonResponse($serializer->serialize($violations, 'json'), Response::HTTP_BAD_REQUEST, [], true);
        }

        $em->persist($group);
        $em->flush();

        return new JsonResponse($serializer->serialize($group, 'json'), Response::HTTP_OK, [], true);
    }

    #[Route('/add/{contactId}', name: 'api_groups_add_contact', methods: ['PUT'])]
    public function addContact(
        Request                $request,
        GroupsRepository       $groupsRepository,
        ContactRepository      $contactRepository,
        SerializerInterface    $serializer,
        string                 $contactId,
        EntityManagerInterface $em
    ): JsonResponse
    {
        try {
            $uuid = Uuid::fromString($contactId);

            $contact = $contactRepository->find($uuid);
        } catch (InvalidArgumentException $ignored) {
            throw new InvalidUuidException();
        }


        $data = $request->getContent();
        $groupsName = $serializer->deserialize($data, 'array', 'json');
        $contactGroups = $contact->getGroups();

        foreach ($groupsName as $groupName) {
            $group = $groupsRepository->findOneBy(['name' => $groupName]);

            if (!$group) {
                $group = new Groups();
                $group->setName($groupName);

                $em->persist($group);
                $em->flush();
            }

            $contact->addGroups($group);

            $em->persist($contact);
            $em->flush();
        }

        // Remove groups that are not in the request
        foreach ($contactGroups as $contactGroup) {
            if (!in_array($contactGroup->getName(), $groupsName)) {
                $contact->removeGroups($contactGroup);

                $em->persist($contact);
                $em->flush();

                if ($contactGroup->getContact()->count() === 0) {
                    $em->remove($contactGroup);
                    $em->flush();

                }
            }
        }

        return new JsonResponse($serializer->serialize($contact, 'json'), Response::HTTP_OK, [], true);
    }
}
