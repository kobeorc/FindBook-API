<?php

namespace App\Models;

class Point
{
    public $x;
    public $y;

    public function __construct($x,$y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public function distanceTo(Point $point) {
        $distanceX = $this->x - $point->x;
        $distanceY = $this->y - $point->y;
        $distance = sqrt($distanceX * $distanceX + $distanceY * $distanceY);
        return $distance;
    }

}