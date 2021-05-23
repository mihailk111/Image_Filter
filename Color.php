<?php

require_once "getValuesInArray.php";

class Color implements getValuesInArray
{
    public function __construct(public int $red, public int $green, public int $blue)
    {        
    }

    public function get(): array
    {
        return [$this->red, $this->green, $this->blue];
    }
}
