<?php
require "abstractFilter.php";

class fourColorFilter extends abstractFilter
{

    private int $areaFindingBlur;

    public function __construct(string $imagePath, int $blur, int $areaFindingBlur, string $outDir, fourColorsPalette $palette)
    {
        parent::__construct($imagePath, $blur, $outDir, $palette);

        $this->areaFindingBlur = $areaFindingBlur;
        $this->outFile = $this->outDir . "/" . "{$this->fileNameNormal}_blacked-" . $this->blur . "-" . $this->areaFindingBlur . "." . $this->pathInfo['extension'];

    }


    public function run()
    {

        $fileName = $this->pathInfo['filename'];
        $typeOfFile = $this->pathInfo['extension'];

        $hardBlurredFileName = $this->createBlurredFile($this->imagePath, $this->areaFindingBlur, time() . $fileName . "_blurred." . $typeOfFile);

        $hardBlurredImage = $this->openImage($hardBlurredFileName);

        $areas = $hardBlurredImage->findDarkAndLightAreas();

        $hardBlurredImage->destroy();

        $blurredFileName = $this->createBlurredFile($this->imagePath, $this->blur);
        $this->image = $this->openImage($blurredFileName);

        exec("rm $blurredFileName");

        $black = $this->palette->black;
        $grey = $this->palette->grey;
        $lightGrey = $this->palette->lightGrey;
        $white = $this->palette->white;

        $blackColor = $this->image->colorAllocate($black->red, $black->green, $black->blue);

        $greyColor = $this->image->colorAllocate($grey->red, $grey->green, $grey->blue);

        $lightGreyColor = $this->image->colorAllocate($lightGrey->red, $lightGrey->green, $lightGrey->blue);

        $whiteColor = $this->image->colorAllocate($white->red, $white->green, $white->blue);

//        $width =  $this->image->width;
//        $height = $this->image->width;

        $lightAverage = $this->countAreaAverage('light', $areas);
        $darkAverage = $this->countAreaAverage('dark', $areas);


        foreach ($areas as $key => &$area) {
            foreach ($area as &$pixel) {
                $x = &$pixel[0];
                $y = &$pixel[1];
                $grey = $this->image->greyAt($x, $y);
                if ($key === "light") {
                    $newColor = ($grey > $lightAverage) ? $whiteColor : $lightGreyColor; //$lightGreyColor; //$blackColor;

                } else {
                    $newColor = ($grey > $darkAverage) ? $greyColor : $blackColor;
                }
                $this->image->setPixel($x, $y, $newColor);

            }
        }

        $this->saveImage($this->imagePath, $this->outFile);

        exec("rm $hardBlurredFileName");
    }

    protected function countAreaAverage(string $type, array &$areas): int|float
    {
        $pixelsSum = 0;
        $count = 0;
        foreach ($areas[$type] as &$pixel) {
            $pixelsSum += $this->image->greyAt($pixel[0], $pixel[1]);
            $count++;
        }

        return $pixelsSum / $count;
    }
}
