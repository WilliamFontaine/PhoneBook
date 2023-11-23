<?php

namespace App\ExceptionHandler;

use App\Exception\InvalidFileException;
use App\Exception\InvalidUuidException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

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
