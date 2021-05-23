<?php
require_once "Color.php";
require_once "colorPalette.php";

class twoColorsPalette implements colorPalette
{
    public function __construct(public Color $black, public Color $white)
    {
    }

    public function get(): array
    {
        return [$this->black, $this->white];
    }
}