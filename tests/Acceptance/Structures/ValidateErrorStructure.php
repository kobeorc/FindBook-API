<?php
declare(strict_types=1);

namespace Tests\Acceptance\Structures;

class ValidateErrorStructure extends AbstractStructure
{
    public static $structure = [
        'message',
        'errors',
        'status',
    ];

    public static $types = [
        'message' => 'string',
        'errors'  => 'array',
        'status'  => 'integer'
    ];
}
