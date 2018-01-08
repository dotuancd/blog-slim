<?php

use Illuminate\Container\Container;

function config($key, $default = null)
{
    $config = app('config');
    return $config->get($key, $default);
}

function app($service = null)
{
    $container = Container::getInstance();
    if ($service === null) {
        return $container;
    }
    return $container->make($service);
}

/**
 * @param $value
 * @param array $options
 * @return mixed
 */
function bcrypt($value, $options = [])
{
    $container = Container::getInstance();
    return $container
        ->make('hash')
        ->make($value, $options);
}