<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Slim\Http\Request;
use App\Http\Response;
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

        $newest = $post->newest()->take(1)->first();
        $oldest = $post->oldest()->take(1)->first();

        if ($newest) {
            $post->next = [
                'id' => $newest->id,
                'title' => $newest->title,
                'slug' => $newest->slug,
            ];
        }
        if ($oldest) {
            $post->prev = [
                'id' => $oldest->id,
                'title' => $oldest->title,
                'slug' => $oldest->slug,
            ];
        }

        return $response->success($post);
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
            'title' => 'required',
            'status' => 'required|in:draft,published'
        ]);
        $user = $this->user($request);

        $data = $request->getParams(['title', 'content']);
        $data['user_id'] = $user->id;
        /** @var Post $post */
        $post = Post::newModelInstance($data);
        $status = $request->getParam('status');
        $isPublished = ($status === 'published');

        if ($isPublished && $userCanPublish) {

        }
        $post->requestReview();

        return $response->created($post);
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

        /** @var User $user */
        $user = $this->user($request);

        if ( ! $user->canWrite($post) ) {
            return $response->forbidden();
        }

        $this->validate($request, [
            'title' => 'required',
        ]);

        $post->update($request->getParams());

        return $response->success($post);
    }
}