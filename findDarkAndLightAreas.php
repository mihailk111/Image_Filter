<?php


/**
*   creates global variable area
 */
function findDarkAndLightAreas(GdImage $image)
{

    $width =  imagesx($image);
    $height = imagesy($image);

    $greyAverage = imageGreyAverage($image);

    $area = ['dark' => [], 'light' => []];

    for ($x = 0; $x < $width; $x++) {
        for ($y = 0; $y < $height; $y++) {


            $newGreyRgbElement = greyAt($image, $x, $y);

            $newColor = $newGreyRgbElement > $greyAverage ? 'white' : 'black';
            if ($newColor === 'white') {
                $area['light'][] = [$x, $y];
            } else {
                $area['dark'][] = [$x, $y];
            }

        }
    }

    return $area;
}