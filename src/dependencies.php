<?php
// DIC configuration

use Slim\DefaultServicesProvider;
use Illuminate\Config\Repository;
use Illuminate\Container\Container;
use App\Providers\AppServiceProvider;
use Illuminate\Events\EventServiceProvider;
use Illuminate\Hashing\HashServiceProvider;
use App\Providers\PaginationServiceProvider;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\DatabaseServiceProvider;
use Illuminate\Filesystem\FilesystemServiceProvider;
use Illuminate\Validation\ValidationServiceProvider;
use Illuminate\Translation\TranslationServiceProvider;

$container = $app->getContainer();

// Instantiate the app
$userSettings = require __DIR__ . '/settings.php';

$defaultSettings = [
    'httpVersion' => '1.1',
    'responseChunkSize' => 4096,
    'outputBuffering' => 'append',
    'determineRouteBeforeAppMiddleware' => false,
    'displayErrorDetails' => true,
    'addContentLengthHeader' => true,
    'routerCacheFile' => false,
];

$config = new Repository(array_merge($defaultSettings, $userSettings));

date_default_timezone_set($config->get('app.timezone'));

$container->instance('container', $container);

$container->alias(Container::class, 'container');
$container->alias(\Illuminate\Contracts\Container\Container::class, 'container' );
$container->alias(Application::class, 'container');

$container->instance('config', $config);
$container->alias('config', 'settings');
$container->instance('path', dirname(__DIR__));
$container->instance('path.lang', $container['path'] . '/resources/lang');
Container::setInstance($container);

$container->make(DefaultServicesProvider::class)->register($container);
$providers = [
    EventServiceProvider::class,
    DatabaseServiceProvider::class,
    HashServiceProvider::class,
    FilesystemServiceProvider::class,
    TranslationServiceProvider::class,
    ValidationServiceProvider::class,
    PaginationServiceProvider::class,

    AppServiceProvider::class,
];

foreach ($providers as $provider) {
    $provider = new $provider($container);

    if (method_exists($provider, 'register')) {
        $provider->register();
    }

    if (method_exists($provider, 'boot')) {
        $container->call([$provider, 'boot']);
    }
}

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};
