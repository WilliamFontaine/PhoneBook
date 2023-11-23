<?php

namespace App\Controller\API;

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
  #[Route('', name: 'app_api_contacts_index', methods: ['GET'])]
  public function index(ContactRepository $contactRepository, SerializerInterface $serializer): Response
  {
    $contacts = $contactRepository->findAll();

    return new JsonResponse($serializer->serialize($contacts, 'json'), Response::HTTP_OK, [], true);
  }

  /**
   * @throws InvalidUuidException
   */
  #[Route('/{id}', name: 'app_api_contacts_get', methods: ['GET'])]
  public function get(SerializerInterface $serializer, ContactRepository $contactRepository, string $id): Response
  {
    try {
      $uuid = Uuid::fromString($id);

      $contact = $contactRepository->find($uuid);

      if (!$contact) {
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
      }

      return new JsonResponse($serializer->serialize($contact, 'json'), Response::HTTP_OK, [], true);
    } catch (InvalidArgumentException $ignored) {
      throw new InvalidUuidException();
    }
  }


  #[Route('', name: 'app_api_contacts_add', methods: ['POST'])]
  public function add(
    Request                $request,
    SerializerInterface    $serializer,
    ValidatorInterface     $validator,
    EntityManagerInterface $em
  ): JsonResponse
  {
    $data = $request->getContent();

    $contact = $serializer->deserialize($data, Contacts::class, 'json');

    $contact->setCreatedAt(new DateTimeImmutable());
    $contact->setUpdatedAt(new DateTimeImmutable());

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
  #[Route('/{id}', name: 'app_api_contacts_update', methods: ['PUT'])]
  public function update(
    Request                $request,
    SerializerInterface    $serializer,
    ValidatorInterface     $validator,
    EntityManagerInterface $em,
    ContactRepository      $contactRepository,
    string                 $id
  ): JsonResponse
  {
    try {
      $uuid = Uuid::fromString($id);

      $contact = $contactRepository->find($uuid);

      if (!$contact) {
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
      }

      $data = $request->getContent();

      $updatedContact = $serializer->deserialize($data, Contacts::class, 'json');

      $contact->setFirstname($updatedContact->getFirstname());
      $contact->setLastname($updatedContact->getLastname());
      $contact->setUpdatedAt(new DateTimeImmutable());

      $existingPhoneContact = $em->getRepository(Contacts::class)->findOneBy(['phone' => $updatedContact->getPhone()]);
      if ($existingPhoneContact && $existingPhoneContact !== $contact) {
        return new JsonResponse([['property_path' => 'phone', 'message' => 'unique']], Response::HTTP_BAD_REQUEST);
      }
      $contact->setPhone($updatedContact->getPhone());

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
  #[Route('/{id}', name: 'app_api_contacts_delete', methods: ['DELETE'])]
  public function delete(
    ContactRepository      $contactRepository,
    EntityManagerInterface $em,
    FileUploader           $fileUploader,
    string                 $id
  ): JsonResponse
  {
    try {
      $uuid = Uuid::fromString($id);

      $contact = $contactRepository->find($uuid);

      if (!$contact) {
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
      }

      if ($contact->getImageName() !== null) {
        $fileUploader->remove($contact->getImageName());
      }

      $em->remove($contact);
      $em->flush();

      return new JsonResponse(['message' => 'Contacts deleted.'], Response::HTTP_OK);
    } catch (InvalidArgumentException $ignored) {
      throw new InvalidUuidException();
    }
  }
}
