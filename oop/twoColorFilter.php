<?php

require_once "./mkFilter.php";

class twoColorFilter extends abstractFilter
{
    public function __construct(string $imagePath, int $blur, string $outDir, twoColorsPalette $palette)
    {
        parent::__construct($imagePath, $blur,  $outDir);
        $this->palette = $palette;
    }
    public function run()
    {

        // $blurredFileName = createBlurredFile($filePath, $blurScale, time() . "{$fileName}_blurred.{$typeOfFile}");
        $blurredFileName = $this->pathInfo['filename'] . "_blurred." . $this->pathInfo['extension'];

        $this->createBlurredFile($this->imagePath, $this->blur, $blurredFileName);

        $this->openImage($blurredFileName);

        $image = $this->image;

        // $imageResource = imagecreatefromstring(file_get_contents($blurredFileName));


        // twoColorsFilter($imageResource, $palette);

        // $greyAverage = imageGreyAverage($image);

        $greyAverage = $image->greyAverage();

        $black = $image->colorAllocate($palette->black->red, $palette->black->green, $palette->black->blue);
        $white = $image->colorAllocate($palette->white->red, $palette->white->green, $palette->white->blue);

        $width =  $image->width;
        $height = $image->height;

        for ($x = 0; $x < $width; $x++) {
            for ($y = 0; $y < $height; $y++) {

                $color = $image->greyAt($x, $y);

                if ($color > $greyAverage) {
                    $image->setPixel($x, $y, $white);
                } else {
                    $image->setPixel($x, $y, $black);
                    // imagesetpixel($image, $x, $y, $black);
                }
            }
        }
        $this->saveImage($this->imagePath, $outFile);

        `rm $blurredFileName`;
    }


    // function twoColorsFilter(GdImage $image, twoColorsPalette $palette)
    // {
    //     $greyAverage = imageGreyAverage($image);

    //     $black = imagecolorallocate($image, $palette->black->red, $palette->black->green, $palette->black->blue);
    //     $white = imagecolorallocate($image, $palette->white->red, $palette->white->green, $palette->white->blue);

    //     $width =  imagesx($image);
    //     $height = imagesy($image);

    //     for ($x = 0; $x < $width; $x++) {
    //         for ($y = 0; $y < $height; $y++) {

    //             $color = greyAt($image, $x, $y);

    //             if ($color > $greyAverage) {
    //                 imagesetpixel($image, $x, $y, $white);
    //             } else {
    //                 imagesetpixel($image, $x, $y, $black);
    //             }
    //         }
    //     }
    // }


}
