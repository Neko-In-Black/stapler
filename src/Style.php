<?php

namespace Neko\Stapler;

use Neko\Stapler\Interfaces\Style as StyleInterface;

class Style implements StyleInterface
{
    /**
     * The name of the style.
     *
     * @var string
     */
    public $name;

    /**
     * The style dimensions.
     * This can be either a string or a callable type.
     *
     * @var mixed
     */
    public $dimensions;

    /**
     * Whether the image should be auto-oriented
     * using embedded EXIF data.
     *
     * @var bool
     */
    public $autoOrient = false;

    /**
     * An array of values used by Imagine Image to control
     * image quality, DPI, etc when saving an image.
     *
     * @var array
     */
    public $convertOptions = [];

    /**
     * Constructor method.
     *
     * @param string $name
     * @param mixed $value
     * @throws Exceptions\InvalidStyleConfigurationException
     *
     */
    public function __construct($name, $value)
    {
        $this->name = $name;

        if (is_array($value)) {
            if (!array_key_exists('dimensions', $value)) {
                throw new Exceptions\InvalidStyleConfigurationException('Error Processing Request', 1);
            }

            $this->dimensions = $value['dimensions'];

            if (array_key_exists('auto_orient', $value)) {
                $this->autoOrient = $value['auto_orient'];
            }

            if (array_key_exists('convert_options', $value)) {
                $this->convertOptions = $value['convert_options'];
            }

            return;
        }

        $this->dimensions = $value;
    }
}
