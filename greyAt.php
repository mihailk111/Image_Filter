<?php

require_once "rgbAt.php";

function greyAt(GdImage $gdImage, int $x, int  $y)
{
    $rgb = rgbAt($gdImage, $x, $y);
    return intval(($rgb['red'] + $rgb['green'] + $rgb['blue']) / 3);
}
