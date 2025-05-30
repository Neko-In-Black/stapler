<?php

namespace Neko\Stapler\Fixtures\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Neko\Stapler\ORM\EloquentTrait;
use Neko\Stapler\ORM\StaplerableInterface;

class Photo extends Eloquent implements StaplerableInterface
{
    use EloquentTrait;

    protected $fillable = ['id'];

    /**
     * Constructor method.
     *
     * @param array $attributes
     */
    public function __construct($attributes = ['id' => 1])
    {
        $this->hasAttachedFile('photo', [
            'styles' => [
                'thumbnail' => '100x100',
            ],
            'url' => '/system/:attachment/:id_partition/:style/:filename',
            'default_url' => '/defaults/:style/missing.png',
            'convert_options' => [
                'thumbnail' => ['quality' => 100, 'auto-orient' => true],
            ],
        ]);

        parent::__construct($attributes);
    }
}
