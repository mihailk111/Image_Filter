<?php

require_once "rgbAt.php";
require_once "./countAverage.php";
require_once "./createBlurredFile.php";

define('AVERAGE_GRAY_RGB_ELEM', 127.5);

const areaFindingBlurScale = 10;
const finalBlur = 2;


function blackWhiteImage(string $filePath, int $blurScale, int $areaFindingBlurScale,  string $outFile = null)
{
    $pathInfo = pathinfo($filePath);

    $typeOfFile = $pathInfo['extension'];
    $fileName = $pathInfo['filename'];

    if (!$outFile) $outFile = "{$fileName}_blacked-" . $blurScale . "-" . $areaFindingBlurScale . ".{$typeOfFile}";
    // if (!$outFile) $outFile = "{$fileName}_blacked-$blurScale.{$typeOfFile}";

    $hardBlurredFileName = time() . "{$fileName}_blurred.{$typeOfFile}";

    // exec("convert $filePath -blur 0x".$areaFindingBlurScale." $hardBlurredFileName");
    createBlurredFile($filePath, $areaFindingBlurScale, $hardBlurredFileName);

    $blurredImageResource = imagecreatefromstring(file_get_contents($hardBlurredFileName));

    $imageResource = blackWhiteImageResource($blurredImageResource, $filePath, $blurScale);

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

    $width =  imagesx($someImage);
    $height = imagesy($someImage);

    $allGraysSum = 0;
    for ($x = 0; $x < $width; $x++) {
        for ($y = 0; $y < $height; $y++) {
            $pixelRgb = rgbAt($someImage, $x, $y);
            $newRgb = intval(($pixelRgb['red'] + $pixelRgb['green'] + $pixelRgb['blue']) / 3);
            $allGraysSum += $newRgb;
        }
    }

    /**
     *  Image grey average
     */
    $average = $allGraysSum / ($width * $height);

    /**
     *  Contains pixels in form $pixel[0] is x $pixel[1] is y
     */
    $area = ['dark' => [], 'light' => []];

    for ($x = 0; $x < $width; $x++) {
        for ($y = 0; $y < $height; $y++) {

            $pixelRgb = rgbAt($someImage, $x, $y);

            $newGreyRgbElement = intval(($pixelRgb['red'] + $pixelRgb['green'] + $pixelRgb['blue']) / 3);

            $newColor = $newGreyRgbElement > $average ? 'white' : 'black';
            if ($newColor === 'white') {
                $area['light'][] = [$x, $y];
            } else {
                $area['dark'][] = [$x, $y];
            }

            // $newColor = 0;
            // if($newGreyRgbElement <= $scaleOf255[0])
            // {
            //     $newColor = $black;
            // }
            // else if ($newGreyRgbElement > $scaleOf255[1] )
            // {
            //     $newColor = $white;
            // }
            // else
            // {
            //     $newColor = $medium_color;
            // }
            // imagesetpixel($someImage, $x, $y, $newColor);
        }
    }

    /**
     *  Destroy hard blured image
     */
    imagedestroy($someImage);

    /**
     *  main file
     */
    $normalFileNameBlurred = createBlurredFile($normalFileName, $blurScale);
    $normalImage = imagecreatefromstring(file_get_contents($normalFileNameBlurred));

    // $black = imagecolorallocate($someImage, 0, 0, 0);
    // $black = imagecolorallocate($someImage, 74, 0, 109);
    // $white = imagecolorallocate($someImage, 255, 255, 255);

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

        unset($lightPixels[$key]);

    }

    foreach ($darkPixels as $key => &$pixel) {
        $x = $pixel[0];
        $y = $pixel[1];
        $grey = greyAt($normalImage, $x, $y);
        $newColor = ($grey > $darkAverage) ? $greyColor : $blackColor;
        imagesetpixel($normalImage, $x, $y, $newColor);

        unset($darkPixels[$key]);
    }
    


    return $normalImage;
}
