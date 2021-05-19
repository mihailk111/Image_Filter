<?php

/**
 * 
 * @return $blurredFileName 
 */
function createBlurredFile(string $fileName, int $blurScale, string $outFile = null):string
{

    $pathInfo = pathinfo($fileName);

    $blurredFileName = isset($outFile) ? $outFile : $pathInfo['filename'] . "_blurred." . $pathInfo['extension'];

    `convert $fileName -blur 0x$blurScale $blurredFileName`;

    return $blurredFileName;
}