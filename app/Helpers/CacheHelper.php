<?php

class CacheHelper
{
    /** @var array  */
    private static $map = [
        'limit',
        'offset',
    ];

    public static function getKeyCache($request): string
    {
        $result = [];
        foreach (self::$map as $item) {
            $row[$item] = $request->get($item);
            $result[] = $row;
        }

        return md5(json_encode($result));
    }
}