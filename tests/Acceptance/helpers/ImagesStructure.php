<?php
declare(strict_types=1);

namespace Tests\Acceptance\helpers;

class ImagesStructure extends AbstractStructure
{
    public static $structure = [
        'id',
        'path',
    ];

    public static $types = [
        'id'   => 'intger',
        'path' => 'string'
    ];
}
