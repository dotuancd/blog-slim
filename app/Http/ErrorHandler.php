<?php

namespace App\Http;

use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ErrorHandler
{
    public function handle($request, Response $response, $exception)
    {
        if ($exception instanceof ValidationException) {
            return $this->convertValidationExceptionToResponse($response, $exception);
        }

        if ($exception instanceof ModelNotFoundException) {
            return $response->notFound();
        }

        return $this->convertExceptionToResponse($response, $exception);
    }

    protected function convertExceptionToResponse(Response $response, \Exception $e)
    {
        return $response->error(500, $e->getMessage(), config('app.debug') ? ['traces' => $e->getTrace()] : []);
    }

    protected function convertValidationExceptionToResponse(Response $response, $exception)
    {
        return $response->validation($exception->validator->errors());
    }
}