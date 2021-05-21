<?php
require "./vendor/autoload.php";
require_once "./normalize.php";
require_once "rgbAt.php";
require_once "./countAverage.php";
require_once "./createBlurredFile.php";
require_once "./imageGreyAverage.php";
require_once "./findDarkAndLightAreas.php";
require_once "./twoColorsFilter.php";
require_once "./fourColorsFilter.php";
require_once "./saveImage.php";


function blackWhiteImage(string $filePath, int $blurScale, int $areaFindingBlurScale, string  $type = "fourColor",  string $outDir, colorPalette $palette)
{
    $pathInfo = pathinfo($filePath);
    $typeOfFile = $pathInfo['extension'];
    $fileName = $pathInfo['filename'];

    $outDir = normalize($outDir);

    $outFile = $outDir . "/" . "{$fileName}_blacked-" . $blurScale . "-" . $areaFindingBlurScale . ".{$typeOfFile}";

    if ($type === "fourColor") {
        $hardBlurredFileName = createBlurredFile($filePath, $areaFindingBlurScale, time() . "{$fileName}_blurred.{$typeOfFile}");

        $hardBlurredImageResource = imagecreatefromstring(file_get_contents($hardBlurredFileName));

        $areas = findDarkAndLightAreas($hardBlurredImageResource);

        imagedestroy($hardBlurredImageResource);

        $imageResource = fourColorsFilter($filePath, $blurScale, $areas, $palette);

        saveImage($imageResource, $filePath, $outFile);

        `rm $hardBlurredFileName`;
    } else if ($type === "twoColor") {

        $blurredFileName = createBlurredFile($filePath, $blurScale, time() . "{$fileName}_blurred.{$typeOfFile}");
        $imageResource = imagecreatefromstring(file_get_contents($blurredFileName));
        twoColorsFilter($imageResource, $palette);

        saveImage($imageResource, $filePath, $outFile);

        `rm $blurredFileName`;
    }
}
