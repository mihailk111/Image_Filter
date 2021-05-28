<?php

require_once "Image.php";


abstract class abstractFilter
{
    protected $pathInfo;
    protected $outDir;
    protected $outFile;
    protected string $imagePath;
    protected int  $blur;
    protected Image $image;
    protected colorPalette $palette;
    protected string $fileNameNormal;

    public function __construct(int $blur, string $outDir, colorPalette $palette)
    {
        $this->outDir = $this->normalizeDir($outDir);
        $this->blur = $blur;
//        $this->imagePath = $imagePath;
//        $this->pathInfo = pathinfo($this->imagePath);
        $this->palette = $palette;

//        $this->fileNameNormal = $this->normalizeName($this->pathInfo['filename']);
    }

    abstract public function run(string $imagePath);
    abstract protected function initializeNames(string $imagePath);

    protected function createBlurredFile(string $fileName, int $blurScale, string $outFile = null): string
    {
        $pathInfo = pathinfo($fileName);
        $blurredFileName = $outFile ?? $pathInfo['filename'] . "_blurred." . $pathInfo['extension'];
        `convert $fileName -blur 0x$blurScale $blurredFileName`;
        return $blurredFileName;
    }

    protected function openImage(string $fileName) : Image
    {
        $image = imagecreatefromstring(file_get_contents($fileName));
        return new Image($image);
    }

    protected function saveImage(string $filePath, string $outFile)
    {
        
        $imageType = image_type_to_extension(exif_imagetype($filePath), false);

        match ($imageType) {
            'jpeg' => imagejpeg($this->image->image, $outFile,  100),
            'png' => imagepng($this->image->image, $outFile, 0),
            'webp' => imagewebp($this->image->image, $outFile, 100),
            default => throw new Error("We support only JPEG WEBP and PNG")
        };

        imagedestroy($this->image->image);
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
        $imageNameNormal .= str_repeat("0", $nullsAmount);

        $imageNameNormal = $imageNameNormal . $match[0];
        return $imageNameNormal;
    }
}
