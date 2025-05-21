<?php

namespace Neko\Stapler\Interfaces;

interface Config
{
    /**
     * Retrieve a configuration value.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function get($name);

    /**
     * Set a configuration value.
     *
     * @param string $name
     * @param mixed $value
     *
     * @return void
     */
    public function set($name, $value);
}
