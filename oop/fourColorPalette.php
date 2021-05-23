<?php

require_once "colorPalette.php";
require_once "Color.php";

class fourColorsPalette implements colorPalette
{
    public function __construct(
        public Color $black,
        public Color $grey,
        public Color $lightGrey,
        public Color $white
    ) {
    }

    public function get():array
    {
        return [$this->black, $this->grey, $this->lightGrey, $this->white];
    }

}