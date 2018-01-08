<?php

namespace App\Middleware;

use App\Models\User;
use App\Support\ApiErrorResponder;
use Illuminate\Container\Container;
use Psr\Http\Message\RequestInterface;

class ApiAuth
{
    protected $inputToken;

    protected $storageToken;

    protected $repository;

    public function __construct(Container $container, $inputToken = 'api_token', $storageToken = 'api_token')
    {
        $this->repository = $container->make(User::class);
        $this->inputToken = $inputToken;
        $this->storageToken = $storageToken;
    }
    
    public function __invoke(RequestInterface $request, $response, $next)
    {
        /** @var \Slim\Http\Request $request */
        $token = $request->getHeader('Authorization');
        $token = preg_replace('/^Bearer\s/', '', $token) ?: $request->getParam('api_token');
        if (!$token) {
            return ApiErrorResponder::make($response)->unauthorized();
        }

        $user = $this->repository->where('api_token', $token)->first();

        if (!$user) {
            return ApiErrorResponder::make($response)->unauthorized();
        }

        $request = $request->withAttribute('user', $user);
        return $next($request, $response);
    }
}