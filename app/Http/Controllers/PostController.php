<?php

namespace App\Http\Controllers;

use App\Container;
use App\Models\Post;
use Illuminate\Validation\Factory;
use Illuminate\Validation\ValidationException;
use Slim\Http\Request;
use Slim\Http\Response;
use Illuminate\Validation\Validator;
use Illuminate\Contracts\Foundation\Application;

class PostController extends Controller
{
    public function index(Request $request, Response $response)
    {
        return $response->withJson(Post::paginate());
    }

    public function show(Request $request, Response $response)
    {
        $post = Post::findOrFail($request->getAttribute('post'));
        $post->user;
        $nextPost = Post::where('id', '>', $post->id)->first();
        $prevPost = Post::where('id', '<', $post->id)->first();
        if ($nextPost) {
            $post->next = [
                'id' => $nextPost->id,
                'title' => $nextPost->title,
            ];
        }
        if ($prevPost) {
            $post->prev = [
                'id' => $prevPost->id,
                'title' => $prevPost->title,
            ];
        }
        return $response->withJson($post);
    }

    public function store($request, $response)
    {
        $this->validate($request, [
            'title' => 'required'
        ]);

        $data = $request->getParams(['title', 'content']);
        $data['user_id'] = $this->user($request)->id;
        $post = Post::create($data);
        return $response->withJson($post);
    }

    public function update(Request $request, $response)
    {
        $post = Post::findOrFail($request->getAttribute('post'));

        $user = $this->user($request);
        if (!$post->isOwnedBy($user)) {
            return $this->forbidden('What are you doing? It is not your.');
        }

        $this->validate($request, [
            'title' => 'required',
        ]);
        $post->update($request->getParams());
        return $response->withJson($post);
    }

    protected function user($request)
    {
        return $request->getAttribute('user');
    }
}