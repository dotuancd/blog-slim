<?php

namespace App\Http\Controllers;

use App\Http\Response;
use App\Models\Post;
use Slim\Http\Request;
use Illuminate\Database\Query\Builder;

class PostController extends Controller
{
    public function index(Request $request, Response $response)
    {
        return $response->success(Post::latest()->paginate());
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

        return $response->success($post);
    }

    public function store($request, Response $response)
    {
        $this->validate($request, [
            'title' => 'required'
        ]);

        $data = $request->getParams(['title', 'content']);
        $data['user_id'] = $this->user($request)->id;
        $post = Post::create($data);

        return $response->created($post);
    }

    public function update(Request $request, Response $response)
    {
        /** @var Post $post */
        $post = Post::findOrFail($request->getAttribute('post'));

        $user = $this->user($request);

        if (!$post->isOwnedBy($user)) {
            return $response->forbidden('What are you doing? It isn\'t your post.');
        }

        $this->validate($request, [
            'title' => 'required',
        ]);

        $post->update($request->getParams());

        return $response->success($post);
    }
}