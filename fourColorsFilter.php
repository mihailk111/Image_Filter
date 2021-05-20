<?php

function fourColorsFilter($fileName, $blurScale, $areas): GdImage
{
    /**
     *  main file
     */
    $blurredFileName = createBlurredFile($fileName, $blurScale);
    $image = imagecreatefromstring(file_get_contents($blurredFileName));


    `rm $blurredFileName`;


    $blackColor = imagecolorallocate($image, 0, 0, 0);
    $greyColor = imagecolorallocate($image, 85, 85, 85);
    $lightGreyColor = imagecolorallocate($image, 170, 170, 170);
    $whiteColor = imagecolorallocate($image, 255, 255, 255);

    $width =  imagesx($image);
    $height = imagesy($image);

    $lightAverage = countAverage('light', $areas, $image);
    $darkAverage = countAverage('dark', $areas, $image);

    $lightPixels = &$areas['light'];
    $darkPixels = &$areas['dark'];

    foreach ($lightPixels as $key => &$pixel) {

        $x = $pixel[0];
        $y = $pixel[1];
        $grey = greyAt($image, $x, $y);
        $newColor = ($grey > $lightAverage) ? $whiteColor : $lightGreyColor; //$blackColor;
        imagesetpixel($image, $x, $y, $newColor);

        // unset($lightPixels[$key]);
    }

    foreach ($darkPixels as $key => &$pixel) {
        $x = $pixel[0];
        $y = $pixel[1];
        $grey = greyAt($image, $x, $y);
        $newColor = ($grey > $darkAverage) ? $greyColor : $blackColor;
        imagesetpixel($image, $x, $y, $newColor);

        // unset($darkPixels[$key]);
    }



    return $image;
}