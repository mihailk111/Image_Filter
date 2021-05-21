<?php


/**
 *  Normalizes path
 */

function normalize(string $dirName)
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
