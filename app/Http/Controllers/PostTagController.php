<?php


namespace App\Http\Controllers;

use App\Models\Tag;
use Slim\Http\Request;
use Slim\Http\Response;

class PostTagController
{
    /**
     * Find all post related to the tag
     *
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function index(Request $request, Response $response)
    {
        $tag = Tag::with('posts', 'posts.user')->where('slug', $request->getAttribute('tag'))->firstOrFail();
        $columns = ['title', 'slug', 'created_at'];
        $posts = $tag->posts()->paginate(null, $columns);
        return $response->withJson($posts);
    }
}