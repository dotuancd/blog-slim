<?php
// Routes

use App\Middleware\ApiAuth;

$app->group('/api', function ($app) {
    $app->get('/posts/{post}', 'App\Http\Controllers\PostController:show');
    $app->get('/posts', 'App\Http\Controllers\PostController:index');
    $app->post('/posts', 'App\Http\Controllers\PostController:store')->add(ApiAuth::class);
    $app->put('/posts/{post}', 'App\Http\Controllers\PostController:update')->add(ApiAuth::class);
    $app->get('/tags/{tag}', 'App\Http\Controllers\PostTagController:index');

    $app->post('/posts/{post}/comments', 'App\Http\Controllers\PostCommentController:store')->add(ApiAuth::class);
    $app->get('/posts/{post}/comments', 'App\Http\Controllers\PostCommentController:index');

    $app->post('/auth', 'App\Http\Controllers\AuthController:login');
    $app->post('/register', 'App\Http\Controllers\AuthController:register');
    $app->get('/me', 'Auth\Http\Controllers\AuthController:me')->add(ApiAuth::class);
    $app->post('/me/change-password', 'App\Http\Controllers\AuthController:changePassword')->add(ApiAuth::class);

    $app->group('/admin', function ($app) {
        $app->get('/posts', 'App\Http\Controllers\Admin\PostController:index')->add(ApiAuth::class);
        $app->get('/posts/{post}', 'App\Http\Controllers\Admin\PostController:show')->add(ApiAuth::class);
    });
});

$app->get('/', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});
