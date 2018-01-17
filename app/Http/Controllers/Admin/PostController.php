<?php

namespace App\Http\Controllers\Admin;

use App\Models\Post;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Http\Controllers\Controller;

class PostController extends Controller
{
    public function index(Request $request, Response $response)
    {
        $user = $this->user($request);
        $posts = Post::forUser($user)->latest()->paginate();
        return $response->withJson($posts);
    }

    public function show(Request $request, Response $response)
    {
        /** @var Post $post */
        $user = $this->user($request);
        $post = Post::forUser($user)->findOrFail($request->getAttribute('post'));
        return $response->withJson($post);
    }
}