<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Exception\InvalidUuidException;
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

class ContactsController extends AbstractController
{
    #[Route('/contacts', name: 'contact.index', methods: ['GET'])]
    public function index(SerializerInterface $serializer, EntityManagerInterface $em): Response
    {
        return $this->render("contacts/index.html.twig");

//        $contacts = $em->getRepository(Contact::class)->findAll();
//
//        return new JsonResponse($serializer->serialize($contacts, 'json'), Response::HTTP_OK, [], true);
    }

    /**
     * @throws InvalidUuidException
     */
    #[Route('/contacts/{id}', name: 'contact.get', methods: ['GET'])]
    public function get(SerializerInterface $serializer, EntityManagerInterface $em, string $id): Response
    {
        try {
            $uuid = Uuid::fromString($id);

            $contact = $em->getRepository(Contact::class)->find($uuid);

            if (!$contact) {
                return new JsonResponse(null, Response::HTTP_NOT_FOUND);
            }

            return new JsonResponse($serializer->serialize($contact, 'json'), Response::HTTP_OK, [], true);
        } catch (InvalidArgumentException $ignored) {
            throw new InvalidUuidException();
        }
    }


    #[Route('/contacts', name: 'contact.add', methods: ['POST'])]
    public function add(ValidatorInterface $validator, SerializerInterface $serializer, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = $request->getContent();
        $contact = $serializer->deserialize($data, Contact::class, 'json');

        $violations = $validator->validate($contact);

        if ($violations->count() > 0) {
            return new JsonResponse($serializer->serialize($violations, 'json'), Response::HTTP_BAD_REQUEST, [], true);
        }

        $em->persist($contact);
        $em->flush();

        return new JsonResponse($serializer->serialize($contact, 'json'), Response::HTTP_CREATED, [], true);
    }

    /**
     * @throws InvalidUuidException
     */
    #[Route('/contacts/{id}', name: 'contact.update', methods: ['PUT'])]
    public function update(ValidatorInterface $validator, SerializerInterface $serializer, Request $request, EntityManagerInterface $em, string $id): JsonResponse
    {
        try {
            $uuid = Uuid::fromString($id);

            $contact = $em->getRepository(Contact::class)->find($uuid);

            if (!$contact) {
                return new JsonResponse(null, Response::HTTP_NOT_FOUND);
            }

            $data = $request->getContent();

            $updatedContact = $serializer->deserialize($data, Contact::class, 'json');

            $contact->setFirstname($updatedContact->getFirstname());
            $contact->setLastname($updatedContact->getLastname());

            $existingPhoneContact = $em->getRepository(Contact::class)->findOneBy(['phone' => $updatedContact->getPhone()]);
            if ($existingPhoneContact && $existingPhoneContact !== $contact) {
                return new JsonResponse(['error' => 'Phone number is already used.'], Response::HTTP_BAD_REQUEST);
            }

            $existingEmailContact = $em->getRepository(Contact::class)->findOneBy(['email' => $updatedContact->getEmail()]);
            if ($existingEmailContact && $existingEmailContact !== $contact) {
                return new JsonResponse(['error' => 'Email is already used.'], Response::HTTP_BAD_REQUEST);
            }

            $contact->setPhone($updatedContact->getPhone());
            $contact->setEmail($updatedContact->getEmail());

            $violations = $validator->validate($contact);

            if ($violations->count() > 0) {
                return new JsonResponse($serializer->serialize($violations, 'json'), Response::HTTP_BAD_REQUEST, [], true);
            }

            $em->flush();

            return new JsonResponse($serializer->serialize($contact, 'json'), Response::HTTP_OK, [], true);
        } catch (InvalidArgumentException $ignored) {
            throw new InvalidUuidException();
        }
    }

    /**
     * @throws InvalidUuidException
     */
    #[Route('/api/contacts/{id}', name: 'contact.delete', methods: ['DELETE'])]
    public function delete(EntityManagerInterface $em, string $id): JsonResponse
    {
        try {
            $uuid = Uuid::fromString($id);

            $contact = $em->getRepository(Contact::class)->find($uuid);

            if (!$contact) {
                return new JsonResponse(null, Response::HTTP_NOT_FOUND);
            }

            $em->remove($contact);
            $em->flush();

            return new JsonResponse(['message' => 'Contact deleted.'], Response::HTTP_OK);
        } catch (InvalidArgumentException $ignored) {
            throw new InvalidUuidException();
        }
    }
}
