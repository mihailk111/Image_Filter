<?php



class fourColorFilter extends mkFilter
{
    public function __construct(string $imagePath, int $blur, int $areaFindingBlur, string $outDir, fourColorsPalette $palette)
    {
        parent::__construct($imagePath, $blur, $outDir);
        $this->palette = $palette;
        $this->areaFindingBlur = $areaFindingBlur;
        
    }
}