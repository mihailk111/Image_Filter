<?php

function twoColorsFilter(GdImage $image, twoColorsPalette $palette)
{
    $greyAverage = imageGreyAverage($image);

    $black = imagecolorallocate($image, $palette->black->red, $palette->black->green, $palette->black->blue);
    $white = imagecolorallocate($image, $palette->white->red, $palette->white->green, $palette->white->blue);

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