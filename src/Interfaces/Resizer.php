<?php

namespace Neko\Stapler\Interfaces;

use Imagine\Image\ImagineInterface;
use Neko\Stapler\Interfaces\File as FileInterface;
use Neko\Stapler\Interfaces\Style as StyleInterface;

interface Resizer
{
    /**
     * Constructor method.
     *
     * @param ImagineInterface $imagine
     */
    public function __construct(ImagineInterface $imagine);

    /**
     * Resize an image using the computed settings.
     *
     * @param FileInterface $file
     * @param Style         $style
     *
     * @return string
     */
    public function resize(FileInterface $file, StyleInterface $style);

    /**
     * Accessor method for the $imagine property.
     *
     * @param ImagineInterface $imagine
     */
    public function setImagine(ImagineInterface $imagine);
}