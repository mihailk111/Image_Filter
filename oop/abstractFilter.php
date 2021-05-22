<?php

require_once "Image.php";

abstract class abstractFilter
{
    protected $pathInfo;
    protected $outDir;
    protected $outFile;
    protected $imagePath;
    protected $blur;

    public function __construct(string $imagePath,  int $blur, string $outDir)
    {
        $this->outDir = $outDir;
        $this->blur = $blur;
        $this->imagePath = $imagePath;

        $this->pathInfo = pathinfo($this->imagePath);

        // $fileName = $pathInfo['filename'];
        // $typeOfFile = $pathInfo['extension'];

        $this->outDir = $this->normalizeDir($this->outDir);

        $fileNameNormal = $this->normalizeName($this->pathInfo['filename']);

        $this->outFile = $this->outDir . "/" . "{$fileNameNormal}_blacked-" . $this->blur . "-" . $this->areaFindingBlurScale ?? "null" . "." . $this->pathInfo['extension'];
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

    protected function saveImage(string $filePath, string $outFile)
    {
        
        $imageType = image_type_to_extension(exif_imagetype($filePath), false);

        match ($imageType) {
            'jpeg' => imagejpeg($this->image, $outFile,  100),
            'png' => imagepng($this->image, $outFile, 0),
            'webp' => imagewebp($this->image, $outFile, 100),
            default => throw new Exception("We support only JPEG WEBP and PNG")
        };

        imagedestroy($this->image);
    }

    public function normalizeDir(string $dirName)
    {
        if ($dirName === "./" || $dirName === ".") return ".";

        $dirNameArray = str_split($dirName);
        if ($dirNameArray[0] === '.' && $dirNameArray[1] === '/') {
            array_shift($dirNameArray);
            array_shift($dirNameArray);
        }
        if ($dirNameArray[count($dirNameArray) - 1] === '/') {
            array_pop($dirNameArray);
        }

        return implode("", $dirNameArray);
    }

    public function normalizeName(string $imageName)
    {
        preg_match("/\d+/", $imageName, $match);
        $lengthOfNumber = strlen($match[0]);
        $nullsAmount = 6 - $lengthOfNumber;

        $imageNameNormal = "img";
        for ($i = 0; $i < $nullsAmount; $i++) {
            $imageNameNormal = $imageNameNormal . "0";
        }

        $imageNameNormal = $imageNameNormal . $match[0];
        return $imageNameNormal;
    }
}
