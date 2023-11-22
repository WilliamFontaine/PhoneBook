<?php

namespace App\ExceptionHandler;

use App\Exception\InvalidFileException;
use App\Exception\InvalidUuidException;
use Doctrine\DBAL\Exception\NotNullConstraintViolationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class CustomExceptionHandler
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof InvalidUuidException) {
            $response = new JsonResponse(['error' => 'Invalid UUID'], 400);
            $event->setResponse($response);
        } else if ($exception instanceof InvalidFileException) {
            $response = new JsonResponse(['error' => 'Invalid file'], 400);
            $event->setResponse($response);
        }
    }
}
