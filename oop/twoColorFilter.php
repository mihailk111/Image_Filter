<?php

require_once "./mkFilter.php";

class twoColorFilter extends mkFilter
{
    public function __construct(string $imagePath, int $blur, string $outDir, twoColorsPalette $palette)
    {
        parent::__construct($imagePath, $blur,  $outDir);
        $this->palette = $palette;
        
    }
    
}