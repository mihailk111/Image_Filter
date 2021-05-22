<?php

require_once "rgbAt.php";

function greyAt(GdImage $gdImage, int $x, int  $y)
{
    $rgb = rgbAt($gdImage, $x, $y);
    return intval((0.3*$rgb['red'] + 0.59*$rgb['green'] + 0.11*$rgb['blue']));
    // return intval(($rgb['red'] + $rgb['green'] + $rgb['blue'])/3);
}
