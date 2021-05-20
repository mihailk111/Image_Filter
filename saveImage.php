<?php

function saveImage(GdImage $image, string $fileName, string $outFile)
{
    $imageType = image_type_to_extension(exif_imagetype($fileName), false);

    match ($imageType) {
        'jpeg' => imagejpeg($image, $outFile,  100),
        'png' => imagepng($image, $outFile, 0),
        'webp' => imagewebp($image, $outFile, 100),
        default => throw new Exception("We support only JPEG WEBP and PNG")
    };

    imagedestroy($image);

}