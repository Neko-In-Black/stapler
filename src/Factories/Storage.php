<?php

namespace Neko\Stapler\Factories;

use Neko\Stapler\Attachment as AttachedFile;
use Neko\Stapler\Stapler;
use Neko\Stapler\Storage\Filesystem;
use Neko\Stapler\Storage\S3;

class Storage
{
    /**
     * Build a storage instance.
     *
     * @param AttachedFile $attachment
     * @return \Neko\Stapler\Storage\Filesystem|\Neko\Stapler\Storage\S3
     */
    public static function create(AttachedFile $attachment)
    {
        switch ($attachment->storage) {
            case 's3':
                $s3Client = Stapler::getS3ClientInstance($attachment);
                return new S3($attachment, $s3Client);
            default:
                return new Filesystem($attachment);
        }
    }
}
