<?php

namespace Neko\Stapler\Interfaces;

interface Style
{
    /**
     * Constructor method.
     *
     * @param string $name
     * @param mixed $value
     * @throws Neko\Stapler\Exceptions\InvalidStyleConfigurationException
     *
     */
    public function __construct($name, $value);
}
