<?php

require_once "rgbAt.php";
require_once "./countAverage.php";
require_once "./createBlurredFile.php";
require_once "./imageGreyAverage.php";
require_once "./findDarkAndLightAreas.php";
require_once "./twoColorsFilter.php";
require_once "./fourColorsFilter.php";



function blackWhiteImage(string $filePath, int $blurScale, int $areaFindingBlurScale, string  $type = "fourColor",  string $outFile = null)
{
    $pathInfo = pathinfo($filePath);
    $typeOfFile = $pathInfo['extension'];
    $fileName = $pathInfo['filename'];

    $imageResource = null;
    $hardBlurredFileName = null;

    if (!$outFile) $outFile = "{$fileName}_blacked-" . $blurScale . "-" . $areaFindingBlurScale . ".{$typeOfFile}";

    if ($type === "fourColor") {
        $hardBlurredFileName = createBlurredFile($filePath, $areaFindingBlurScale, time() . "{$fileName}_blurred.{$typeOfFile}");

        $hardBlurredImageResource = imagecreatefromstring(file_get_contents($hardBlurredFileName));

        $areas = findDarkAndLightAreas($hardBlurredImageResource);

        imagedestroy($hardBlurredImageResource);

        $imageResource = fourColorsFilter($filePath, $blurScale, $areas);

    } 
    else if ($type === "twoColor") {

        $blurredFileName = createBlurredFile($filePath, $blurScale, time() . "{$fileName}_blurred.{$typeOfFile}");
        $imageResource = imagecreatefromstring(file_get_contents($blurredFileName));
        twoColorsFilter($imageResource);

    }


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




