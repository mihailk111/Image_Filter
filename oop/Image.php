<?php

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


    protected function findDarkAndLightAreas(): array
    {

        $greyAverage = $this->greyAverage();

        $area = ['dark' => [], 'light' => []];

        for ($x = 0; $x < $this->width; $x++) {
            for ($y = 0; $y < $this->height; $y++) {


                $newGreyRgbElement = $this->greyAt($x, $y);

                $newColor = $newGreyRgbElement > $greyAverage ? 'white' : 'black';
                if ($newColor === 'white') {
                    $area['light'][] = [$x, $y];
                } else {
                    $area['dark'][] = [$x, $y];
                }
            }
        }

        return $area;
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
        $colorIndex = imagecolorallocate($this->image, $red, $green, $blue);
        return $colorIndex;
    }

    public function setPixel(int $x, int $y, int $colorIndex)
    {
        imagesetpixel($this->image, $x, $y, $colorIndex); 
    }
}
