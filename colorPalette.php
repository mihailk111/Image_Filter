<?php
spl_autoload_register(function ($class) {
        require_once $class. ".php";

});

interface colorPalette extends getValuesInArray
{
}



