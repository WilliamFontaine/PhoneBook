<?php

namespace App\Controller\API;

use App\Exception\InvalidFileException;
use App\Exception\InvalidUuidException;
use App\Repository\ContactRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

#[Route('/api/images')]
class ImageController extends AbstractController
{
    public function __construct(private readonly string $targetDirectory)
    {
    }

    /**
     * @throws InvalidUuidException
     * @throws InvalidFileException
     */
    #[Route('/{contact_id}', name: 'app_api_images_post', methods: ['POST'])]
    public function post(
        Request                $request,
        FileUploader           $fileUploader,
        ContactRepository      $contactRepository,
        EntityManagerInterface $em,
        string                 $contact_id
    ): BinaryFileResponse
    {
        try {
            $uuid = Uuid::fromString($contact_id);

            $contact = $contactRepository->find($uuid);

            if ($contact->getImageName() !== null) {
                $fileUploader->remove($contact->getImageName());
            }

            $file = $request->files->get('profilePicture');

            if ($file instanceof UploadedFile) {
                $newFile = $fileUploader->upload($file);
                $contact->setImageFile($newFile);

                $contact->setImageName($newFile->getFilename());

                $em->flush();

                return new BinaryFileResponse($newFile->getPathname());
            } else {
                throw new InvalidFileException();
            }
        } catch (InvalidArgumentException $ignored) {
            throw new InvalidUuidException();
        }
    }


    #[Route('/{filename}', name: 'app_api_images_get', methods: ['GET'])]
    public function get(string $filename): BinaryFileResponse
    {
        $path = $this->targetDirectory . "/" . $filename;

        return new BinaryFileResponse($path);
    }

    /**
     * @throws InvalidUuidException
     */
    #[Route('/{contact_id}', name: 'app_api_images_delete', methods: ['DELETE'])]
    public function delete(
        ContactRepository      $contactRepository,
        FileUploader           $fileUploader,
        EntityManagerInterface $em,
        string                 $contact_id
    ): JsonResponse
    {
        try {
            $uuid = Uuid::fromString($contact_id);

            $contact = $contactRepository->find($uuid);

            if ($contact->getImageName() !== null) {
                $fileUploader->remove($contact->getImageName());
            }

            $contact->setImageFile(null);
            $contact->setImageName(null);

            $em->persist($contact);
            $em->flush();

            return new JsonResponse(null, Response::HTTP_OK);
        } catch (InvalidArgumentException $ignored) {
            throw new InvalidUuidException();
        }
    }

}
