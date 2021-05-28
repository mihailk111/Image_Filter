<?php

use JetBrains\PhpStorm\ArrayShape;

class Image
{
    public GdImage $image;

    public function __construct(GdImage $image)
    {
        $this->image = $image;
        $this->width =  imagesx($image);
        $this->height = imagesy($image);
    }

    public function greyAt(int $x, int $y) :int
    {
        $rgb = $this->rgbAt($x, $y);
        return intval((0.3 * $rgb['red'] + 0.59 * $rgb['green'] + 0.11 * $rgb['blue']));
    }

    protected function rgbAt(int $x, int $y): array
    {
        $pixelColorIndex = imagecolorat($this->image, $x, $y);
        return imagecolorsforindex($this->image, $pixelColorIndex);
    }


    #[ArrayShape(['dark' => "array", 'light' => "array"])]
    public function findDarkAndLightAreas(): array
    {

        $greyAverage = $this->greyAverage();

        $area = ['dark' => [], 'light' => []];

        for ($x = 0; $x < $this->width; $x++) {
            for ($y = 0; $y < $this->height; $y++) {


                $newGreyRgbElement = $this->greyAt($x, $y);

                $newColor = $newGreyRgbElement > $greyAverage ? 'white' : 'black';
                if ($newColor === 'white') {
                    $this->addPixel($area['light'], [$x, $y]);
                } else {
                    $this->addPixel($area['dark'], [$x, $y]);
                }
            }
        }
        $this->optimizePixels($area);
        return $area;
    }

    private function optimizePixels(array &$areas)
    {
        $toRanges = function (array $y_s) {
            $resultArray = [];
            $currentArray = [];

            foreach ($y_s as $y) {
                if (empty($currentArray[0])) {
                   $currentArray []= $y;
                }
                else
                {
                    $last = $currentArray[array_key_last($currentArray)];
                    if ($last - $y === 1) {
                        $currentArray []= $y;
                    }
                    else
                    {
                        $resultArray []= [ $currentArray[array_key_last($currentArray)], $currentArray[0] ];
                        $currentArray = [];
                    }
                }
            }

            if (isset($currentArray[0])) {
                $resultArray []= [ $currentArray[array_key_last($currentArray)], $currentArray[0] ];
            }
            return $resultArray;
        };

        foreach ($areas as &$area) {
            foreach ($area as &$x_array) {
                rsort($x_array);
                $x_array = $toRanges($x_array);
            }
        }
    }

    private function addPixel(array &$area, array $pixel)
    {
        $x = $pixel[0];
        $y = $pixel[1];
        if (isset($area[$x])) {
            $area[$x] [] = $y;
        } else {
            $area[$x] = [];
            $area[$x] [] = $y;
        }

    }

    public function greyAverage(): float|int
    {

        $allGraysSum = 0;
        for ($x = 0; $x < $this->width; $x++) {
            for ($y = 0; $y < $this->height; $y++) {

                $newRgb = $this->greyAt($x, $y);
                $allGraysSum += $newRgb;
            }
        }

        return  $allGraysSum / ($this->width * $this->height);
    }

    public function colorAllocate(int $red, int $green, int $blue): bool|int
    {
        return imagecolorallocate($this->image, $red, $green, $blue);
    }

    /**
     *  unset($this->image); image is GdImage
     */
    public function destroy()
    {
        unset($this->image);
    }
    public function setPixel(int $x, int $y, int $colorIndex)
    {
        imagesetpixel($this->image, $x, $y, $colorIndex); 
    }
}
