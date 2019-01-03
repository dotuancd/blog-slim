<?php

namespace App\Http;

use Slim\Http\Response as HttpResponse;
use App\Http\Response\ErrorBuilder;

class Response extends HttpResponse
{
    public function notFound($message = 'Not found')
    {
        return ErrorBuilder::make($this)->notFound($message);
    }

    public function validation($fields = [], $message = 'Oops, Needs to check the data in request')
    {
        return ErrorBuilder::make($this)->validation($fields, $message);
    }

    public function unauthorized($message = 'Unauthorized')
    {
        return ErrorBuilder::make($this)->unauthorized($message);
    }

    public function forbidden($message = 'Forbidden')
    {
        return ErrorBuilder::make($this)->forbidden($message);
    }
    
    public function error($code = 500, $message = 'Oops, something wrong. Please try later.', $appends = [])
    {
        return ErrorBuilder::make($this)->format($code, $message, $appends);
    }

    public function success($data = [], $status = 200)
    {
        return $this->withJson($data, $status);
    }

    public function created($data = [])
    {
        return $this->success($data, 201);
    }
}