<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Slim\Http\Request;
use Slim\Http\Response;
use Illuminate\Database\Query\Builder;

class PostController extends Controller
{
    /**
     * @param Request $request
     * @param Response $response
     * @return static
     */
    public function index(Request $request, Response $response)
    {
        if ($request->getParam('author')) {
        }
        return $response->withJson(Post::latest()->paginate());
    }

    public function show(Request $request, Response $response)
    {
        /** @var Post|\Illuminate\Database\Eloquent\Builder|Builder $post */
        $post = Post::with([
            'user',
            'tags:name,slug,posts_count'
        ]);

        $post->whereSlug($request->getAttribute('post'));
        $post = $post->firstOrFail();

        $nextPost = Post::where('id', '>', $post->id)->oldest()->first();
        $prevPost = Post::where('id', '<', $post->id)->latest()->first();
        if ($nextPost) {
            $post->next = [
                'id' => $nextPost->id,
                'title' => $nextPost->title,
                'slug' => $nextPost->slug,
            ];
        }
        if ($prevPost) {
            $post->prev = [
                'id' => $prevPost->id,
                'title' => $prevPost->title,
                'slug' => $prevPost->slug,
            ];
        }
        return $response->withJson($post);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return static
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, Response $response)
    {
        $this->validate($request, [
            'title' => 'required'
        ]);

        $data = $request->getParams(['title', 'content']);
        $data['user_id'] = $this->user($request)->id;
        $post = Post::create($data);
        return $response->withJson($post);
    }

    /**
     * @param Request $request
     * @param $response
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Response $response)
    {
        /** @var Post $post */
        $post = Post::findOrFail($request->getAttribute('post'));

        $user = $this->user($request);
        if (!$post->isOwnedBy($user)) {
            return $this->forbidden('What are you doing? It is not your post.');
        }

        $this->validate($request, [
            'title' => 'required',
        ]);
        $post->update($request->getParams());
        return $response->withJson($post);
    }
}