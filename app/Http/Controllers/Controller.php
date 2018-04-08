<?php

namespace App\Http\Controllers;

use App\Container;
use App\Http\Exception\ForbiddenException;
use App\Models\User;
use App\Support\ApiErrorResponder;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator;
use Slim\Http\Request;

class Controller
{
    const ALLOWED_FOR_ALL = '*';
    protected $permissions = [];

    protected $application;

    public function __construct(Container $application)
    {
        $this->application = $application;
    }

    /**
     * @param User $user
     * @param $action
     * @return bool
     */
    protected function checkPermission(User $user, $action)
    {
        if (!array_has($this->permissions, $action)) {
            return false;
        }

        $requiredRoles = array_get($this->permissions, $action);

        if ($requiredRoles == '*') {
            return true;
        }

        if (!is_array($requiredRoles)) {
            $requiredRoles = [$requiredRoles];
        }

        return in_array($user->role, $requiredRoles);
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