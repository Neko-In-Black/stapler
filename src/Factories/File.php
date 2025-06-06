<?php

namespace Neko\Stapler\Factories;

use Neko\Stapler\Exceptions\FileException;
use Neko\Stapler\File\File as StaplerFile;
use Neko\Stapler\File\UploadedFile as StaplerUploadedFile;
use Neko\Stapler\Interfaces\Config as ConfigInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile as SymfonyUploadedFile;
use Symfony\Component\Mime\MimeTypes;

class File
{
    /**
     * A instance of Symfony's MIME type extension guesser interface.
     *
     * @var Symfony\Component\Mime\MimeTypes
     */
    private static $mimeTypeManager;

    /**
     * Build a Neko\Stapler\UploadedFile object using various file input types.
     *
     * @param mixed $file
     * @param bool $testing
     *
     * @throws \Neko\Stapler\Exceptions\FileException
     */
    public static function create($file, $testing = false)
    {
        if ($file instanceof SymfonyUploadedFile) {
            return static::createFromObject($file);
        }

        if (is_array($file)) {
            return static::createFromArray($file, $testing);
        }

        if (substr($file, 0, 7) == 'http://' || substr($file, 0, 8) == 'https://') {
            return static::createFromUrl($file);
        }

        if (preg_match('#^data:[-\w]+/[-\w\+\.]+;base64#', $file)) {
            return static::createFromDataURI($file);
        }

        return static::createFromString($file);
    }

    /**
     * Compose a \Neko\Stapler\File\UploadedFile object from
     * a \Symfony\Component\HttpFoundation\File\UploadedFile object.
     *
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     * @return \Neko\Stapler\File\UploadedFile
     * @throws \Neko\Stapler\Exceptions\FileException
     */
    protected static function createFromObject(SymfonyUploadedFile $file)
    {
        $staplerFile = new StaplerUploadedFile($file);
        $staplerFile->validate();

        return $staplerFile;
    }

    /**
     * Compose a \Neko\Stapler\File\UploadedFile object from a
     * data uri.
     *
     * @param string $file
     * @return \Neko\Stapler\File\File
     * @throws \Neko\Stapler\Exceptions\FileException
     */
    protected static function createFromDataURI($file)
    {
        $fp = @fopen($file, 'r');

        if (!$fp) {
            throw new FileException('Invalid data URI');
        }

        $meta = stream_get_meta_data($fp);
        $extensions = static::getMimeTypeManager()->getExtensions($meta['mediatype']);
        $extension = $extensions[0] ?? 'png';
        $filePath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . md5($meta['uri']) . '.' . $extension;

        file_put_contents($filePath, stream_get_contents($fp));

        return new StaplerFile($filePath);
    }

    /**
     * Build a Neko\Stapler\File\File object from the
     * raw php $_FILES array date.  We assume here that the $_FILES array
     * has been formatted using the Stapler::arrangeFiles utility method.
     *
     * @param array $file
     * @param bool $testing
     *
     * @return \Neko\Stapler\File\UploadedFile
     * @throws \Neko\Stapler\Exceptions\FileException
     */
    protected static function createFromArray(array $file, $testing)
    {
        $file = new SymfonyUploadedFile($file['tmp_name'], $file['name'], $file['type'], $file['error'], $testing);

        return static::createFromObject($file);
    }

    /**
     * Fetch a remote file using a string URL and convert it into
     * an instance of Neko\Stapler\File\File.
     *
     * @param string $file
     *
     * @return \Neko\Stapler\File\File
     */
    protected static function createFromUrl($file)
    {
        $ch = curl_init($file);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $rawFile = curl_exec($ch);
        curl_close($ch);

        //TODO: try to recreate with this
        //$request = Request::create($file);

        // Remove the query string and hash if they exist
        $file = preg_replace('/[&#\?].*/', '', $file);

        // Get the original name of the file
        $pathinfo = pathinfo($file);
        $name = $pathinfo['basename'];
        $extension = isset($pathinfo['extension']) ? '.' . $pathinfo['extension'] : '';

        // Create a temporary file with a unique name.
        $tempFile = tempnam(sys_get_temp_dir(), 'stapler-');

        if ($extension) {
            $filePath = "$tempFile.{$extension}";
        } else {
            // Since we don't have an extension for the file, we'll have to go ahead and write
            // the contents of the rawfile to disk (using the tempFile path) in order to use
            // symfony's mime type guesser to generate an extension for the file.
            file_put_contents($tempFile, $rawFile);
            $mimeType = self::getMimeTypeManager()->guessMimeType($tempFile);
            $extensions = static::getMimeTypeManager()->getExtensions($mimeType);
            $extension = explode('', $extensions);
            $filePath = $tempFile . '.' . $extension;
        }

        file_put_contents($filePath, $rawFile);
        unlink($tempFile);

        return new StaplerFile($filePath);
    }

    /**
     * Fetch a local file using a string location and convert it into
     * an instance of \Neko\Stapler\File\File.
     *
     * @param string $file
     *
     * @return \Neko\Stapler\File\File
     */
    protected static function createFromString($file)
    {
        return new StaplerFile($file, pathinfo($file)['basename']);
    }

    /**
     * Return an instance of the Symfony MIME type extension guesser.
     */
    public static function getMimeTypeManager()
    {
        if (!static::$mimeTypeManager) {
            static::$mimeTypeManager = new MimeTypes();
        }

        return static::$mimeTypeManager;
    }

    /**
     * Set the configuration object instance.
     *
     * @param ConfigInterface $config
     */
    public static function setConfigInstance(ConfigInterface $config)
    {
        static::$config = $config;
    }
}
