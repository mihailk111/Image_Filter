<?php

require_once "rgbAt.php";
require_once "./countAverage.php";
require_once "./createBlurredFile.php";
require_once "./imageGreyAverage.php";
require_once "./findDarkAndLightAreas.php";

define('AVERAGE_GRAY_RGB_ELEM', 127.5);

class Type 
{
    static $fourColor = 1;
    static $twoColor = 2;
    static $twoColorExtended = 3;
}


function blackWhiteImage(string $filePath, int $blurScale, int $areaFindingBlurScale, int  $type = 1,  string $outFile = null)
{
    $pathInfo = pathinfo($filePath);
    $typeOfFile = $pathInfo['extension'];
    $fileName = $pathInfo['filename'];

    if (!$outFile) $outFile = "{$fileName}_blacked-" . $blurScale . "-" . $areaFindingBlurScale . ".{$typeOfFile}";


    $hardBlurredFileName = createBlurredFile($filePath, $areaFindingBlurScale, time() . "{$fileName}_blurred.{$typeOfFile}");

    $hardBlurredImageResource = imagecreatefromstring(file_get_contents($hardBlurredFileName));

    $areas = findDarkAndLightAreas($hardBlurredImageResource);
    imagedestroy($hardBlurredImageResource);

    $imageResource = fourColorsFilter($filePath, $blurScale, $areas);

    $imageType = image_type_to_extension(exif_imagetype($filePath), false);

    match ($imageType) {
        'jpeg' => imagejpeg($imageResource, $outFile,  100),
        'png' => imagepng($imageResource, $outFile, 0),
        'webp' => imagewebp($imageResource, $outFile, 100),
        default => throw new Exception("We support only JPEG WEBP and PNG")
    };

    echo "saved at $outFile\n";

    imagedestroy($imageResource);

    `rm $hardBlurredFileName`;
}





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

    $lightAverage = countAverage('light', $area, $image);
    $darkAverage = countAverage('dark', $area, $image);

    $lightPixels = &$area['light'];
    $darkPixels = &$area['dark'];

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

