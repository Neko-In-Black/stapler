<?php

namespace Neko\Stapler\Interfaces;

interface Style
{
    /**
     * Constructor method.
     *
     * @throws Neko\Stapler\Exceptions\InvalidStyleConfigurationException
     *
     * @param string $name
     * @param mixed  $value
     */
    public function __construct($name, $value);
}
