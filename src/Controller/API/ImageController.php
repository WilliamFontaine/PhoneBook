<?php

namespace App\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/images')]
class ImageController extends AbstractController
{
    public function __construct(private readonly string $targetDirectory) {}


    #[Route('/{filename}', name: 'app_api_images_get', methods: ['GET'])]
    public function get(string $filename): BinaryFileResponse
    {
        $path = $this->targetDirectory . "/" . $filename;

        return new BinaryFileResponse($path);
    }
}
