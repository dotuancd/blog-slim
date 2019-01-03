<?php

namespace App;

use Psr\Container\ContainerInterface;
use Illuminate\Container\Container as IlluminateContainer;

class Container extends IlluminateContainer implements ContainerInterface
{
    public function offsetSet($key, $value)
    {
        $this->singleton($key, $value);
    }

    public function databasePath()
    {
        return $this->get('path.database');
    }

    public function environment()
    {
        return $this->get('config')->get('app.env');
    }

    public function getNamespace()
    {
        return __NAMESPACE__;
    }
}