<?php

namespace App\Http;

use App\Support\ApiErrorResponder;
use Slim\Exception\NotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Validator;
use Illuminate\Validation\ValidationException;

class ErrorHandler
{
    public function handle($request, $response, $exception)
    {
        if ($exception instanceof ValidationException) {
            return $this->convertValidationExceptionToResponse($response, $exception);
        }

        if ($exception instanceof ModelNotFoundException) {
            return (new ApiErrorResponder($response))->notFound();
        }

        return $this->convertExceptionToResponse($response, $exception);
    }

    protected function convertExceptionToResponse($response, \Exception $e)
    {
        dd($e);
        $body = $this->formatErrorResponse(500, $e->getMessage());
        return $response->withJson($body, 500);
    }

    protected function convertValidationExceptionToResponse($response, $exception)
    {
        $body = $this->formatErrorResponse(422, $exception->getMessage(), [
            'params' => $exception->validator->errors(),
        ]);

        return $response->withJson($body, 422);
    }

    protected function formatErrorResponse($code = 500, $message = 'An error occurred', $append = [])
    {
        return array_merge([
            'error' => true,
            'code' => $code,
            'message' => $message,
        ], $append);
    }
}