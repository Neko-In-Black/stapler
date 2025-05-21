<?php

namespace Neko\Stapler\Storage;

use Aws\S3\S3Client;
use Neko\Stapler\Attachment;
use Neko\Stapler\Interfaces\Storage as StorageInterface;

class S3 implements StorageInterface
{

    /**
     * Boolean flag indicating if this attachment's bucket currently exists.
     *
     * @var array
     */
    protected $bucketExists = false;

    /**
     * Constructor method.
     *
     * @param Attachment $attachedFile The current attachedFile object being processed.
     * @param S3Client $s3Client The AWS S3Client instance.
     */
    public function __construct(public Attachment $attachedFile, protected S3Client $s3Client)
    {
    }

    /**
     * Return the url for a file upload.
     *
     * @param string $styleName
     *
     * @return string
     */
    public function url($styleName)
    {
        return $this->s3Client->getObjectUrl($this->attachedFile->s3_object_config['Bucket'], $this->path($styleName));
    }

    /**
     * Return the key the uploaded file object is stored under within a bucket.
     *
     * @param string $styleName
     *
     * @return string
     */
    public function path($styleName)
    {
        return $this->attachedFile->getInterpolator()->interpolate($this->attachedFile->path, $this->attachedFile, $styleName);
    }

    /**
     * Remove an attached file.
     *
     * @param array $filePaths
     */
    public function remove(array $filePaths)
    {
        if ($filePaths) {
            $this->s3Client->deleteObjects(['Bucket' => $this->attachedFile->s3_object_config['Bucket'], 'Objects' => $this->getKeys($filePaths)]);
        }
    }

    /**
     * Move an uploaded file to it's intended destination.
     *
     * @param string $file
     * @param string $filePath
     */
    public function move($file, $filePath)
    {
        $objectConfig = $this->attachedFile->s3_object_config;
        $fileSpecificConfig = ['Key' => $filePath, 'SourceFile' => $file, 'ContentType' => $this->attachedFile->contentType()];
        $mergedConfig = array_merge($objectConfig, $fileSpecificConfig);

        $this->ensureBucketExists($mergedConfig['Bucket']);
        $this->s3Client->putObject($mergedConfig);

        @unlink($file);
    }

    /**
     * Return an array of paths (bucket keys) for an attachment.
     * There will be one path for each of the attachmetn's styles.
     *
     * @param  $filePaths
     *
     * @return array
     */
    protected function getKeys($filePaths)
    {
        $keys = [];

        foreach ($filePaths as $filePath) {
            $keys[] = ['Key' => $filePath];
        }

        return $keys;
    }

    /**
     * Ensure that a given S3 bucket exists.
     *
     * @param string $bucketName
     */
    protected function ensureBucketExists($bucketName)
    {
        if (!$this->bucketExists) {
            $this->buildBucket($bucketName);
        }
    }

    /**
     * Attempt to build a bucket (if it doesn't already exist).
     *
     * @param string $bucketName
     */
    protected function buildBucket($bucketName)
    {
        if (!$this->s3Client->doesBucketExist($bucketName)) {
            $this->s3Client->createBucket(['ACL' => $this->attachedFile->ACL, 'Bucket' => $bucketName, 'LocationConstraint' => $this->attachedFile->region]);
        }

        $this->bucketExists = true;
    }
}
