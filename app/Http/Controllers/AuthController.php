<?php

namespace App\Http\Controllers;

use App\Models\User;
use Slim\Http\Request;
use App\Http\Response;

class AuthController extends Controller
{
    private function getHasher()
    {
        return $this->getApplication()->get('hash');
    }

    public function login(Request $request, Response $response)
    {
        $credentials = [
            'email' => $request->getParam('email'),
            'password' => $request->getParam('password'),
        ];

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !$this->getHasher()->check($credentials['password'], $user->password)) {
            return $response->unauthorized();
        }

        return $response->success($user);
    }

    public function register(Request $request, Response $response)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->getParam('name'),
            'email' => $request->getParam('email'),
            'password' => $this->getHasher()->make($request->getParam('password')),
            'api_token' => str_random(64)
        ]);

        return $response->created($user);
    }
}