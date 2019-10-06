<?php
declare(strict_types=1);

namespace Tests\Acceptance\helpers;

class UserStructure extends AbstractStructure
{
    public static $structure = [
        'id',
        'name',
        'email',
        'role',
        'status',
        'created_at',
        'updated_at',
        'avatar',
    ];
    public static $types     = [
        'id'         => 'integer',
        'name'       => 'string',
        'email'      => 'string',
        'role'       => 'string',
        'status'     => 'string',
        'created_at' => 'string',
        'updated_at' => 'string',
        'avatar'     => 'string',
    ];
}
