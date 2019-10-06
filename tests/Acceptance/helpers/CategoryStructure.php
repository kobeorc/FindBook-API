<?php
declare(strict_types=1);

namespace Tests\Acceptance\helpers;

class CategoryStructure extends AbstractStructure
{
    public static $structure = [
        'id',
        'name',
        'updated_at',
    ];

    public static $types = [
        'id'         => 'integer',
        'name'       => 'string',
        'updated_at' => 'string',
    ];
}
