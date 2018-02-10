<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Models\Comment;

class PostCommentController extends Controller
{
    public function index(Request $request, Response $response)
    {
        $limit = $request->getParam('limit', 10);
        $post = Post::findOrFail($request->getAttribute('post'));
        $comments = $post->comments()
            ->with('user:id,name')
            ->latest()
            ->paginate($limit);

        return $response->withJson($comments);
    }

    public function store(Request $request, Response $response)
    {
        $this->validate($request, [
            'content' => 'required'
        ]);
        $post = Post::findOrFail($request->getAttribute('post'));
        $user = $request->getAttribute('user');

        $comment = Comment::create([
            'user' => $user,
            'post' => $post,
            'content' => $request->getParam('content')
        ]);

        return $response->withJson($comment);
    }
}