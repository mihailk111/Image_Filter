<?php


function normalName(string $imageName)
{
    preg_match("/\d+/", $imageName, $match);
    $lengthOfNumber = strlen($match[0]);
    $nullsAmount = 6 - $lengthOfNumber;

    $imageNameNormal = "img";
    for ($i = 0; $i < $nullsAmount; $i++)
    {
        $imageNameNormal = $imageNameNormal . "0"  ;
    }

    $imageNameNormal = $imageNameNormal . $match[0];
    return $imageNameNormal;
}

