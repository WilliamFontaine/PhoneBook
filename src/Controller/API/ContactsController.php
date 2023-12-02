<?php

namespace App\Controller\API;

use App\Entity\ContactExtendedFields;
use App\Entity\Contacts;
use App\Exception\InvalidUuidException;
use App\Repository\ContactRepository;
use App\Service\FileUploader;
use DateTimeImmutable;
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

#[Route('/api/contacts')]
class ContactsController extends AbstractController
{
    public function __construct(
        private readonly ContactRepository      $contactRepository,
        private readonly SerializerInterface    $serializer,
        private readonly ValidatorInterface     $validator,
        private readonly EntityManagerInterface $em,
        private readonly FileUploader           $fileUploader)
    {
    }

    #[Route('', name: 'app_api_contacts_index', methods: ['GET'])]
    public function index(): Response
    {
        $contacts = $this->contactRepository->findAll();

        return new JsonResponse($this->serializer->serialize($contacts, 'json'), Response::HTTP_OK, [], true);
    }

    /**
     * @throws InvalidUuidException
     */
    #[Route('/{id}', name: 'app_api_contacts_get', methods: ['GET'])]
    public function get(string $id): Response
    {
        try {
            $uuid = Uuid::fromString($id);

            $contact = $this->contactRepository->find($uuid);

            if (!$contact) {
                return new JsonResponse(null, Response::HTTP_NOT_FOUND);
            }

            return new JsonResponse($this->serializer->serialize($contact, 'json'), Response::HTTP_OK, [], true);
        } catch (InvalidArgumentException $ignored) {
            throw new InvalidUuidException();
        }
    }


    #[Route('', name: 'app_api_contacts_add', methods: ['POST'])]
    public function add(
        Request $request
    ): JsonResponse
    {
        $data = $request->getContent();

        unset(json_decode($data, true)["contact_extended_fields"]);

        $contact = $this->serializer->deserialize($data, Contacts::class, 'json');

        $contact->setCreatedAt(new DateTimeImmutable());
        $contact->setUpdatedAt(new DateTimeImmutable());

        $violations = $this->validator->validate($contact);

        if ($violations->count() > 0) {
            return new JsonResponse($this->serializer->serialize($violations, 'json'), Response::HTTP_BAD_REQUEST, [], true);
        }
        $contact = $this->manageExtendedFields($contact);

        $this->em->persist($contact);
        $this->em->flush();


        return new JsonResponse($this->serializer->serialize($contact, 'json'), Response::HTTP_CREATED, [], true);
    }

    private function manageExtendedFields($contact): Contacts
    {
        $extendedFields = $contact->getContactExtendedFields();
        $extendedFieldsNames = [];

        foreach ($extendedFields as $field) {
            $field->setContact($contact);
            $extendedFieldsNames[] = $field->getFieldName();
        }

        $contactExtendedFields = $this->em->getRepository(ContactExtendedFields::class)->findBy(['contact' => $contact]);
        foreach ($contactExtendedFields as $contactExtendedField) {

            if (!in_array($contactExtendedField->getFieldName(), $extendedFieldsNames)) {
                $this->em->remove($contactExtendedField);
            }
        }
        $this->em->flush();

        return $contact;
    }

    /**
     * @throws InvalidUuidException
     */
    #[Route('/{id}', name: 'app_api_contacts_update', methods: ['PUT'])]
    public function update(
        Request $request,
        string  $id
    ): JsonResponse
    {
        try {
            $uuid = Uuid::fromString($id);

            $contact = $this->contactRepository->find($uuid);

            if (!$contact) {
                return new JsonResponse(null, Response::HTTP_NOT_FOUND);
            }

            $data = $request->getContent();

            $updatedContact = $this->serializer->deserialize($data, Contacts::class, 'json');

            $contact->setFirstname($updatedContact->getFirstname());
            $contact->setLastname($updatedContact->getLastname());
            $contact->setUpdatedAt(new DateTimeImmutable());

            $existingPhoneContact = $this->em->getRepository(Contacts::class)->findOneBy(['phone' => $updatedContact->getPhone()]);
            if ($existingPhoneContact && $existingPhoneContact !== $contact) {
                return new JsonResponse([['property_path' => 'phone', 'message' => 'unique']], Response::HTTP_BAD_REQUEST);
            }
            $contact->setPhone($updatedContact->getPhone());
            $contact->setEmail($updatedContact->getEmail());

            $violations = $this->validator->validate($updatedContact);

            if ($violations->count() > 0) {
                return new JsonResponse($this->serializer->serialize($violations, 'json'), Response::HTTP_BAD_REQUEST, [], true);
            }

            $contact = $this->manageExtendedFields($contact);

            $this->em->persist($contact);
            $this->em->flush();

            return new JsonResponse($this->serializer->serialize($contact, 'json'), Response::HTTP_OK, [], true);
        } catch (InvalidArgumentException $ignored) {
            throw new InvalidUuidException();
        }
    }

    /**
     * @throws InvalidUuidException
     */
    #[Route('/{id}', name: 'app_api_contacts_delete', methods: ['DELETE'])]
    public function delete(
        ContactRepository $contactRepository,
        string            $id
    ): JsonResponse
    {
        try {
            $uuid = Uuid::fromString($id);

            $contact = $contactRepository->find($uuid);

            if (!$contact) {
                return new JsonResponse(null, Response::HTTP_NOT_FOUND);
            }

            if ($contact->getImageName() !== null) {
                $this->fileUploader->remove($contact->getImageName());
            }

            $extendedFields = $contact->getContactExtendedFields();
            foreach ($extendedFields as $field) {
                $this->em->remove($field);
            }

            $this->em->remove($contact);
            $this->em->flush();

            return new JsonResponse(['message' => 'Contacts deleted.'], Response::HTTP_OK);
        } catch (InvalidArgumentException $ignored) {
            throw new InvalidUuidException();
        }
    }
}
