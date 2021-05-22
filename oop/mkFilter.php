<?php

require_once "Image.php";

abstract class mkFilter
{
    public function __construct(public string $imagePath, public int $blur, public string $outDir)
    {
        $pathInfo = pathinfo($this->filePath);
        $typeOfFile = $pathInfo['extension'];
        $fileName = $pathInfo['filename'];

        $outDir = normalize($outDir);

        $fileNameNormal = normalName($fileName);

        $outFile = $outDir . "/" . "{$fileNameNormal}_blacked-" . $this->blur . "-" . $this->areaFindingBlurScale ?? "null" . ".{$typeOfFile}";
    }


    protected function createBlurredFile(string $fileName, int $blurScale, string $outFile = null): string
    {
        $pathInfo = pathinfo($fileName);
        $blurredFileName = isset($outFile) ? $outFile : $pathInfo['filename'] . "_blurred." . $pathInfo['extension'];
        `convert $fileName -blur 0x$blurScale $blurredFileName`;
        return $blurredFileName;
    }

    protected function openImage(string $fileName)
    {
        $image = imagecreatefromstring(file_get_contents($fileName));
        $this->image = new mkImage($image);
    }
}
