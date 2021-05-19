<?php

require_once "./greyAt.php";


function countAverage(string $type, array &$area, GdImage $image)
{
    $pixelsSum = 0;
    $count = 0;
    foreach ($area[$type] as &$pixel) {
        $pixelsSum += greyAt($image, $pixel[0], $pixel[1]);
        $count ++;
    }

    return $pixelsSum / $count;
}