<?php

use App\Container;
use Illuminate\Config\Repository;
use Illuminate\Console\Application;
use Illuminate\Database\ConnectionResolver;
use Illuminate\Database\ConnectionResolverInterface;
use Illuminate\Database\Console\Migrations\FreshCommand;
use Illuminate\Database\Console\Migrations\RollbackCommand;
use Illuminate\Database\Console\Migrations\StatusCommand;
use Illuminate\Hashing\HashServiceProvider;
use Illuminate\Support\Facades\Facade;
use Illuminate\Events\EventServiceProvider;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Migrations\Migrator;
use Illuminate\Database\DatabaseServiceProvider;
use Illuminate\Database\MigrationServiceProvider;
use Illuminate\Database\Console\Seeds\SeedCommand;
use Illuminate\Filesystem\FilesystemServiceProvider;
use Illuminate\Database\Console\Migrations\ResetCommand;
use Illuminate\Database\Console\Seeds\SeederMakeCommand;
use Illuminate\Database\Console\Migrations\InstallCommand;
use Illuminate\Database\Console\Migrations\MigrateCommand;
use Illuminate\Database\Console\Migrations\RefreshCommand;
use Illuminate\Database\Console\Migrations\MigrateMakeCommand;
use Illuminate\Database\Migrations\MigrationRepositoryInterface;

require 'vendor/autoload.php';

$container = Container::getInstance();
$container->instance('container', $container);
$container->alias('container', \Illuminate\Contracts\Container\Container::class);
$container->instance('path', __DIR__);
$container->instance('path.database', $container['path'] . '/database');
$container->bind(ConnectionResolverInterface::class, ConnectionResolver::class);

$config = new Repository([
    'database' => require('config/database.php')
]);

$container->instance('config', $config);

$container->when(Application::class)
    ->needs('$version')->give('1.0.0');

Facade::setFacadeApplication($container);

$files = new FilesystemServiceProvider($container);
$files->register();

$events = new EventServiceProvider($container);
$events->register();
$container->alias('events', Dispatcher::class);
$database = new DatabaseServiceProvider($container);
$database->register();
$database->boot();

$service = new MigrationServiceProvider($container);
$service->register();
$container->alias('migration.repository', MigrationRepositoryInterface::class);
$container->alias('migrator', Migrator::class);
$schema = $container['db']->connection()->getSchemaBuilder();
$schema::defaultStringLength(191);

$hash = new HashServiceProvider($container);
$hash->register();

$app = $container->make(Application::class);
/** @var $app Application */
//$app = new Application($container, $container->make('events'), '1.0.0');


$app->addCommands([
    $container->make(MigrateCommand::class),
    $container->make(MigrateMakeCommand::class),
    $container->make(InstallCommand::class),
    $container->make(RefreshCommand::class),
    $container->make(FreshCommand::class),
    $container->make(ResetCommand::class),
    $container->make(SeederMakeCommand::class),
    $container->make(SeedCommand::class),
    $container->make(RollbackCommand::class),
    $container->make(StatusCommand::class),
]);
$app->run();
