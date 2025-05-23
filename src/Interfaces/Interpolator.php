<?php

namespace Neko\Stapler\Interfaces;

use Neko\Stapler\Interfaces\Attachment as AttachmentInterface;

interface Interpolator
{
    /**
     * Interpolate a string.
     *
     * @param string $string
     * @param AttachmentInterface $attachment
     * @param string $styleName
     *
     * @return string
     */
    public function interpolate($string, AttachmentInterface $attachment, $styleName = '');
}