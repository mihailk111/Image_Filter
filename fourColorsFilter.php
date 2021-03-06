<?php
require "abstractFilter.php";

class fourColorsFilter extends abstractFilter
{

    private int $areaFindingBlur;

    public function __construct(int $blur, int $areaFindingBlur, string $outDir, fourColorsPalette $palette)
    {
        parent::__construct($blur, $outDir, $palette);
        $this->areaFindingBlur = $areaFindingBlur;

    }

    protected function initializeNames(string $imagePath)
    {

        $this->imagePath = $imagePath;
        $this->pathInfo = pathinfo($this->imagePath);
        $this->fileNameNormal = $this->pathInfo['filename']; //$this->normalizeName($this->pathInfo['filename']);
        $this->outFile = $this->outDir . "/" . "{$this->fileNameNormal}_blacked-" . $this->blur . "-" . $this->areaFindingBlur . "." . $this->pathInfo['extension'];

    }


    public function run(string $imagePath)
    {
        $this->initializeNames($imagePath);

        $fileName = $this->pathInfo['filename'];
        $typeOfFile = $this->pathInfo['extension'];

        $hardBlurredFileName = $this->createBlurredFile($this->imagePath, $this->areaFindingBlur, time() . $fileName . "_blurred." . $typeOfFile);

        $hardBlurredImage = $this->openImage($hardBlurredFileName);

        $areas = $hardBlurredImage->findDarkAndLightAreas();

        $hardBlurredImage->destroy();

        $blurredFileName = $this->createBlurredFile($this->imagePath, $this->blur);
        $this->image = $this->openImage($blurredFileName);

        exec("rm $blurredFileName");

        $palette = $this->palette->get();
        $blackColor = $this->image->colorAllocate(...$palette[0]->get());

        $greyColor = $this->image->colorAllocate(...$palette[1]->get());

        $lightGreyColor = $this->image->colorAllocate(...$palette[2]->get());

        $whiteColor = $this->image->colorAllocate(...$palette[3]->get());

        $lightAverage = $this->countAreaAverage('light', $areas);
        $darkAverage = $this->countAreaAverage('dark', $areas);


        foreach ($areas as $areaType => $area) {
            foreach ($area as $x => $y_array) {
                foreach ($y_array as $y_range) {
                    foreach (range($y_range[0], $y_range[1]) as $y) {

                        $grey = $this->image->greyAt($x, $y);
                        if ($areaType === "light") {
                            $newColor = ($grey > $lightAverage) ? $whiteColor : $lightGreyColor;

                        } else {
                            $newColor = ($grey > $darkAverage) ? $greyColor : $blackColor;
                        }
                        $this->image->setPixel($x, $y, $newColor);


                    }
                }
            }
        }


        $this->saveImage($this->imagePath, $this->outFile);

        exec("rm $hardBlurredFileName");
    }

    protected function countAreaAverage(string $type, array &$areas): int|float
    {
        $pixelsSum = 0;
        $count = 0;

        foreach ($areas[$type] as $x => $y_s) {
            foreach ($y_s as $y_array) {
                foreach (range($y_array[0], $y_array[1]) as $y) {
                    $pixelsSum += $this->image->greyAt($x, $y);
                    $count++;
                }
            }
        }
        return $pixelsSum / $count;
    }
}
