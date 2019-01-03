<?php

namespace App\Http\Controllers;

use App\Container;
use App\Http\Response\ErrorBuilder;
use Slim\Http\Request;
use Illuminate\Validation\Validator;
use Illuminate\Validation\ValidationException;

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
        $validatorFactory = $this->getApplication()->make('validator');

        $validator = $validatorFactory->make($request->getParams(), $rules, $messages, $attributes);
        /** @var Validator $validator */

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
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