<?php
declare(strict_types=1);

namespace Tests\Acceptance\Structures;

class AuthorsStructure extends AbstractStructure
{
    public static $structure = [
        'id',
        'full_name',
        'type',
    ];

    public static $types = [
        'id'        => 'integer',
        'full_name' => 'string',
        'type'      => 'string',
    ];
}
