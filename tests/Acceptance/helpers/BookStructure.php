<?php
declare(strict_types=1);

namespace Tests\Acceptance\helpers;

class BookStructure extends AbstractStructure
{
    public static $structure = [
        'id',
        'name',
        'description',
        'latitude',
        'longitude',
        'address',
        'year',
        'updated_at',
        'archived_at',
        'status',
        'is_favorite',
        'authors',
        'publishers',
        'categories',
        'users',
        'images',
    ];

    public static $types = [
        'id'          => 'integer',
        'name'        => 'string',
        'description' => 'string',
        'latitude'    => 'float',
        'longitude'   => 'double',
        'address'     => 'string',
        'year'        => 'integer',
        'updated_at'  => 'string',
        'archived_at' => 'string',
        'status'      => 'string',
        'is_favorite' => 'boolean',
        'authors'     => 'array',
        'publishers'  => 'array',
        'categories'  => 'array',
        'users'       => 'array',
        'images'      => 'array',
    ];
}
