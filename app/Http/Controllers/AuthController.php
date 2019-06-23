<?php

namespace App\Http\Controllers;

use App\Models\User;
use Slim\Http\Request;
use App\Http\Response;
use Illuminate\Contracts\Hashing\Hasher;

class AuthController extends Controller
{
    /**
     * @return Hasher
     */
    private function getHasher()
    {
        return $this->getApplication()->get('hash');
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws \Illuminate\Container\EntryNotFoundException
     */
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

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws \Illuminate\Container\EntryNotFoundException
     * @throws \Illuminate\Validation\ValidationException
     */
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

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function changePassword(Request $request, Response $response)
    {
        /** @var User $user */
        $user = $request->getAttribute('user');

        $this->validate($request, [
            'current_password' => 'required',
            'password' => 'required',
        ]);

        $currentPassword = $request->getParam('current_password');

        $hasher = $this->getHasher();
        if (!$hasher->check($currentPassword, $user->password)) {
            return $response->withJson([
                'error' => true,
                'message' => 'The password is incorrect'
            ]);
        }

        $user->password = $hasher->make($request->getParam('password'));
        $user->save();

        return $response->withJson([
            'error' => false,
            'message' => 'The password was changed successfully'
        ]);
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