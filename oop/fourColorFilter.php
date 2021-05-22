<?php

class fourColorFilter extends abstractFilter {

    public function __construct(string $imagePath, int $blur, int $areaFindingBlur, string $outDir, fourColorsPalette $palette) {
        parent::__construct($imagePath, $blur, $outDir);
        $this->palette = $palette;
        $this->areaFindingBlur = $areaFindingBlur;
    }

    public function run($param) {

        $hardBlurredFileName = createBlurredFile($filePath, $areaFindingBlurScale, time() . "{$fileName}_blurred.{$typeOfFile}");

        $hardBlurredImageResource = imagecreatefromstring(file_get_contents($hardBlurredFileName));

        $areas = findDarkAndLightAreas($hardBlurredImageResource);

        imagedestroy($hardBlurredImageResource);

        $imageResource = fourColorsFilter($filePath, $blurScale, $areas, $palette);

        saveImage($imageResource, $filePath, $outFile);

        `rm $hardBlurredFileName`;
    }

}
