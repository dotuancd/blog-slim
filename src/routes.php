<?php
// Routes

use App\Middleware\ApiAuth;

$app->group('/api', function () use ($app) {
    $app->get('/posts/{post}', 'App\Http\Controllers\PostController:show');
    $app->get('/posts', 'App\Http\Controllers\PostController:index');
    $app->post('/posts', 'App\Http\Controllers\PostController:store')->add(ApiAuth::class);
    $app->put('/posts/{post}', 'App\Http\Controllers\PostController:update')->add(ApiAuth::class);

    $app->post('/auth', 'App\Http\Controllers\AuthController:login');
});

$app->get('/', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});
