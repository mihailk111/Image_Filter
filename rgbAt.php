<?php
function rgbAt(GDImage $image, int $x, int $y): array
{
    $pixelColorIndex = imagecolorat($image, $x, $y);
    return imagecolorsforindex($image, $pixelColorIndex);
}