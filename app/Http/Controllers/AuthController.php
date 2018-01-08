<?php

namespace App\Http\Controllers;

use App\Models\User;
use Slim\Http\Request;
use Slim\Http\Response;

class AuthController
{
    protected $hash;

    public function __construct($app)
    {
        $this->hash = $app['hash'];
    }
    
    public function login(Request $request, Response $response)
    {
//        $user = Illuminate\Auth\AuthManager::
        $credentials = [
            'email' => $request->getParam('email'),
            'password' => $request->getParam('password'),
        ];

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !$this->hash->check($credentials['password'], $user->password)) {
            return $this->unauthenticated($response);
        }

        return $response->withJson($user);
    }

    protected function unauthenticated(Response $response)
    {
        return $response->withStatus(401)
        ->withJson([
            'error' => true,
            'message' => 'Unauthenticated'
        ]);
    }
}