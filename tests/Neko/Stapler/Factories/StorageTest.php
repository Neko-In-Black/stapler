<?php

namespace Neko\Stapler\Factories;

use Mockery as m;
use PHPUnit_Framework_TestCase;

class StorageTest extends PHPUnit_Framework_TestCase
{
    /**
     * Setup method.
     */
    public function setUp()
    {
    }

    /**
     * Teardown method.
     */
    public function tearDown()
    {
        m::close();
    }

    /**
     * Test that the Storage factory can create an instance of the filesystem
     * storage driver.
     *
     * @test
     */
    public function it_should_be_able_to_create_a_filesystem_storeage_instance()
    {
        $attachment = $this->buildMockAttachment('filesystem');

        $storage = Storage::create($attachment);

        $this->assertInstanceOf('Neko\Stapler\Storage\Filesystem', $storage);
    }

    /**
     * Test that the Storage factory can create an instance of the s3
     * storage driver.
     *
     * @test
     */
    public function it_should_be_able_to_create_an_s3_storeage_instance()
    {
        $attachment = $this->buildMockAttachment('s3');

        $storage = Storage::create($attachment);

        $this->assertInstanceOf('Neko\Stapler\Storage\S3', $storage);
    }

    /**
     * Test that the Storage factory should create an instance of the filesystem
     * storage driver by default.
     *
     * @test
     */
    public function it_should_be_able_to_create_a_filesystem_storeage_instance_by_default()
    {
        $attachment = $this->buildMockAttachment();

        $storage = Storage::create($attachment);

        $this->assertInstanceOf('Neko\Stapler\Storage\Filesystem', $storage);
    }

    /**
     * Build a mock attachment object.
     *
     * @param string $type
     *
     * @return \Neko\Stapler\Attachment
     */
    protected function buildMockAttachment($type = null)
    {
        $attachment = m::mock('Neko\Stapler\Attachment')->makePartial();
        $attachmentConfig = new \Neko\Stapler\AttachmentConfig('testAttachmentConfig', ['styles' => []]);
        $attachment->setConfig($attachmentConfig);
        $attachment->storage = $type;

        return $attachment;
    }
}
