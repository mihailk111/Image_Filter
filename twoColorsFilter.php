<?php

function twoColorsFilter(GdImage $image)
{
    $greyAverage = imageGreyAverage($image);
    $black = imagecolorallocate($image, 0, 0, 0);
    $white = imagecolorallocate($image, 255, 255, 255);

    $width =  imagesx($image);
    $height = imagesy($image);

    for ($x = 0; $x < $width; $x++) {
        for ($y = 0; $y < $height; $y++) {

            $color = greyAt($image, $x, $y);

            if ($color > $greyAverage) {
                imagesetpixel($image, $x, $y, $white);
            } else {
                imagesetpixel($image, $x, $y, $black);
            }
        }
    }
}