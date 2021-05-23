<?php

require_once "./abstractFilter.php";

class twoColorFilter extends abstractFilter
{
    public function __construct(string $imagePath, int $blur, string $outDir, twoColorsPalette $palette)
    {
        parent::__construct($imagePath, $blur,  $outDir, $palette);
        $this->outFile = $this->outDir . "/" . "{$this->fileNameNormal}_blacked-" . $this->blur . "." . $this->pathInfo['extension'];

    }
    public function run()
    {

        // $blurredFileName = createBlurredFile($filePath, $blurScale, time() . "{$fileName}_blurred.{$typeOfFile}");
        $blurredFileName = $this->pathInfo['filename'] . "_blurred." . $this->pathInfo['extension'];

        $this->createBlurredFile($this->imagePath, $this->blur, $blurredFileName);

        $this->openImage($blurredFileName);

        $image = $this->image;


        $greyAverage = $image->greyAverage();

        $palette = $this->palette;
        //TODO DO IT MORE CUTE WAY
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
                }
            }
        }

        $this->saveImage($this->imagePath, $this->outFile);

        exec("rm $blurredFileName");
    }

}
