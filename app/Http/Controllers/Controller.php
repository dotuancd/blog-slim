<?php

namespace App\Http\Controllers;

use App\Container;
use App\Http\Exception\ForbiddenException;
use App\Support\ApiErrorResponder;
use Illuminate\Validation\ValidationException;
use Slim\Http\Request;

class Controller
{
    protected $application;

    public function __construct(Container $application)
    {
        $this->application = $application;
    }

    protected function getApplication()
    {
        return $this->application;
    }

    protected function validate($request, $rules, $messages = [], $attributes = [])
    {
        /** @var Validator $validator */
        $validatorFactory = $this->getApplication()->make('validator');

        $validator = $validatorFactory->make($request->getParams(), $rules, $messages, $attributes);

        if ($validator->failed()) {
            throw new ValidationException($validator);
        }
    }

    protected function forbidden($message = 'Forbidden')
    {
        $response = $this->getApplication()->get('response');
        return ApiErrorResponder::make($response)->forbidden($message);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    protected function user(Request $request)
    {
        return $request->getAttribute('user');
    }
}