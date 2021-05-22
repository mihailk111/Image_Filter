<?php

class fourColorFilter extends mkFilter {

    public function __construct(string $imagePath, int $blur, int $areaFindingBlur, string $outDir, fourColorsPalette $palette) {
        parent::__construct($imagePath, $blur, $outDir);
        $this->palette = $palette;
        $this->areaFindingBlur = $areaFindingBlur;
    }

    public function run($param) {
        /**
         *  main file
         */
        $blurredFileName = createBlurredFile($fileName, $blurScale);
        $image = imagecreatefromstring(file_get_contents($blurredFileName));

        `rm $blurredFileName`;

        $black = $palette->black;
        $grey = $palette->grey;
        $lightGrey = $palette->lightGrey;
        $white = $palette->white;

        $blackColor = imagecolorallocate($image, $black->red, $black->green, $black->blue);
        $greyColor = imagecolorallocate($image, $grey->red, $grey->green, $grey->blue);
        $lightGreyColor = imagecolorallocate($image, $lightGrey->red, $lightGrey->green, $lightGrey->blue);
        $whiteColor = imagecolorallocate($image, $image, $black->red, $black->green, $black->blue);

        $width = imagesx($image);
        $height = imagesy($image);

        $lightAverage = countAverage('light', $areas, $image);
        $darkAverage = countAverage('dark', $areas, $image);

        $lightPixels = &$areas['light'];
        $darkPixels = &$areas['dark'];

        foreach ($lightPixels as $key => &$pixel) {

            $x = $pixel[0];
            $y = $pixel[1];
            $grey = greyAt($image, $x, $y);
            $newColor = ($grey > $lightAverage) ? $whiteColor : $lightGreyColor; //$lightGreyColor; //$blackColor;
            imagesetpixel($image, $x, $y, $newColor);
        }

        foreach ($darkPixels as $key => &$pixel) {
            $x = $pixel[0];
            $y = $pixel[1];
            $grey = greyAt($image, $x, $y);
            $newColor = ($grey > $darkAverage) ? $greyColor : $blackColor;
            imagesetpixel($image, $x, $y, $newColor);
        }
        return $image;
    }

}
