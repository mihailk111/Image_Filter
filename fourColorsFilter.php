<?php

function fourColorsFilter($fileName, $blurScale, $areas, array $palette ): GdImage
{
    /**
     *  main file
     */
    $blurredFileName = createBlurredFile($fileName, $blurScale);
    $image = imagecreatefromstring(file_get_contents($blurredFileName));


    `rm $blurredFileName`;

    $black =  $palette[3];
    $grey = $palette[2];
    $lightGrey = $palette[1];
    $white = $palette[0];

    $blackColor = imagecolorallocate($image, $black[0], $black[1], $black[2]);
    $greyColor = imagecolorallocate($image, $grey[0], $grey[1], $grey[2]);
    $lightGreyColor = imagecolorallocate($image, $lightGrey[0], $lightGrey[1], $lightGrey[2]);
    $whiteColor = imagecolorallocate($image, $image, $black[0], $black[1], $black[2]);

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
        $newColor = ($grey > $lightAverage) ? $whiteColor : $lightGreyColor;//$lightGreyColor; //$blackColor;
        imagesetpixel($image, $x, $y, $newColor);

    }

    foreach ($darkPixels as $key => &$pixel) {
        $x = $pixel[0];
        $y = $pixel[1];
        $grey = greyAt($image, $x, $y);
        $newColor = ($grey > $darkAverage) ? $greyColor : $blackColor;
        imagesetpixel($image, $x, $y, $newColor);

    }



    return $image;
}