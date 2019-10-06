<?php
declare(strict_types=1);

namespace Tests\Acceptance\helpers;

class PublisherStructure extends AbstractStructure
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
