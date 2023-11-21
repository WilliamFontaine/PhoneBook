<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    public function __construct(private readonly string $targetDirectory) {}

    public function upload(UploadedFile $file): File
    {
        $fileName = md5(uniqid()) . '.' . $file->guessExtension();
        return $file->move($this->targetDirectory, $fileName);
    }
}
