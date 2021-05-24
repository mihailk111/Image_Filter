<?php

require_once "./abstractFilter.php";

class twoColorFilter extends abstractFilter
{
    public function __construct(int $blur, string $outDir, twoColorsPalette $palette)
    {
        parent::__construct($blur, $outDir, $palette);

    }

    protected function initializeNames(string $imagePath)
    {
        $this->imagePath = $imagePath;
        $this->pathInfo = pathinfo($this->imagePath);
        $this->fileNameNormal = $this->normalizeName($this->pathInfo['filename']);
        $this->outFile = $this->outDir . "/" . "{$this->fileNameNormal}_blacked-" . $this->blur . "." . $this->pathInfo['extension'];

    }

    public function run(string $imagePath)
    {
        $this->initializeNames($imagePath);

        $blurredFileName = $this->pathInfo['filename'] . "_blurred." . $this->pathInfo['extension'];

        $this->createBlurredFile($this->imagePath, $this->blur, $blurredFileName);

        $this->image = $this->openImage($blurredFileName);

        $image = $this->image;

        $greyAverage = $image->greyAverage();

        $palette = $this->palette->get();
        $black = $image->colorAllocate(...$palette[0]->get());
        $white = $image->colorAllocate(...$palette[1]->get());


        for ($x = 0; $x < $image->width; $x++) {
            for ($y = 0; $y < $image->height; $y++) {

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
