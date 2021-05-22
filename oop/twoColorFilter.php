<?php

require_once "./mkFilter.php";

class twoColorFilter extends mkFilter
{
    public function __construct(string $imagePath, int $blur, string $outDir, twoColorsPalette $palette)
    {
        parent::__construct($imagePath, $blur,  $outDir);
        $this->palette = $palette;
        
    }
    public function run()
    {

        // $blurredFileName = createBlurredFile($filePath, $blurScale, time() . "{$fileName}_blurred.{$typeOfFile}");

        $blurredFileName = $this->createBlurredFile($this->imagePath, $this->blur, time() . "{$fileName}_blurred.{$typeOfFile}");

        $imageResource = imagecreatefromstring(file_get_contents($blurredFileName));
        twoColorsFilter($imageResource, $palette);

        saveImage($imageResource, $filePath, $outFile);

        `rm $blurredFileName`;
    }
    
}