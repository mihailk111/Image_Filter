<?php

require_once "rgbAt.php";
require_once "./countAverage.php";
require_once "./createBlurredFile.php";
require_once "./imageGreyAverage.php";
require_once "./findDarkAndLightAreas.php";

define('AVERAGE_GRAY_RGB_ELEM', 127.5);


function blackWhiteImage(string $filePath, int $blurScale, int $areaFindingBlurScale,  string $outFile = null)
{
    $pathInfo = pathinfo($filePath);
    $typeOfFile = $pathInfo['extension'];
    $fileName = $pathInfo['filename'];

    if (!$outFile) $outFile = "{$fileName}_blacked-" . $blurScale . "-" . $areaFindingBlurScale . ".{$typeOfFile}";


    $hardBlurredFileName = createBlurredFile($filePath, $areaFindingBlurScale, time() . "{$fileName}_blurred.{$typeOfFile}");

    $hardBlurredImageResource = imagecreatefromstring(file_get_contents($hardBlurredFileName));

    // r(imaola$blurredImageResource
    // ;

    $imageResource = blackWhiteImageResource($hardBlurredImageResource, $filePath, $blurScale);

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





function blackWhiteImageResource(GdImage $someImage, $normalFileName, $blurScale): GdImage
{


    $area = findDarkAndLightAreas($someImage);

    /**
     *  Destroy hard blured image
     */
    imagedestroy($someImage);

    /**
     *  main file
     */
    $normalFileNameBlurred = createBlurredFile($normalFileName, $blurScale);
    $normalImage = imagecreatefromstring(file_get_contents($normalFileNameBlurred));


    `rm $normalFileNameBlurred`;


    $blackColor = imagecolorallocate($normalImage, 0, 0, 0);
    $greyColor = imagecolorallocate($normalImage, 85, 85, 85);
    $lightGreyColor = imagecolorallocate($normalImage, 170, 170, 170);
    $whiteColor = imagecolorallocate($normalImage, 255, 255, 255);

    $width =  imagesx($normalImage);
    $height = imagesy($normalImage);

    $lightAverage = countAverage('light', $area, $normalImage);
    $darkAverage = countAverage('dark', $area, $normalImage);

    $lightPixels = &$area['light'];
    $darkPixels = &$area['dark'];

    foreach ($lightPixels as $key => &$pixel) {

        $x = $pixel[0];
        $y = $pixel[1];
        $grey = greyAt($normalImage, $x, $y);
        $newColor = ($grey > $lightAverage) ? $whiteColor : $lightGreyColor; //$blackColor;
        imagesetpixel($normalImage, $x, $y, $newColor);

        // unset($lightPixels[$key]);
    }

    foreach ($darkPixels as $key => &$pixel) {
        $x = $pixel[0];
        $y = $pixel[1];
        $grey = greyAt($normalImage, $x, $y);
        $newColor = ($grey > $darkAverage) ? $greyColor : $blackColor;
        imagesetpixel($normalImage, $x, $y, $newColor);

        // unset($darkPixels[$key]);
    }



    return $normalImage;
}
