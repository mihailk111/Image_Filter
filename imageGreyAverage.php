<?php


function imageGreyAverage(GdImage $image)
{
    $width =  imagesx($image);
    $height = imagesy($image);

    $allGraysSum = 0;
    for ($x = 0; $x < $width; $x++) {
        for ($y = 0; $y < $height; $y++) {

            $newRgb = greyAt($image, $x, $y);
            $allGraysSum += $newRgb;

        }
    }

    return  $allGraysSum / ($width * $height);
}