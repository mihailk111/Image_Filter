<?php

require_once "Color.php";


interface colorPalette
{
}


class fourColorsPalette implements colorPalette
{
    public function __construct(
        public Color $black,
        public Color $grey,
        public Color $lightGrey,
        public Color $white
    ) {
    }
}

class twoColorsPalette implements colorPalette
{
    public function __construct(public Color $black, public Color $white)
    {
    }
}
