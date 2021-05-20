<?php

require_once "./blackWhiteImage.php";
require_once "./colorPalette.php";

$black = new Color(0,0,0);
$grey = new Color(85,85,85);
$lightGrey = new Color(170, 170, 170);
$white = new Color(255,255,255);

$darkPurple = new Color(95, 9, 115);
$twitchColorPalette = new twoColorsPalette($darkPurple, $white);


$i = 2;
$j = 0;


$lastImageProcessed = file_get_contents("./lastImageProcessed.txt");

$dir = scandir("./images");
$dir = array_filter($dir, function ($elem){
    return str_ends_with($elem, ".jpg"); 
});

// $dir = array_filter($dir, function($elem){
//     preg_match("/\d+/", $elem, $match);
//     $numberInElem = $match[0]; 
// });
// preg_match("/\d+/","out124.jpg", $match)

foreach ($dir as $file) {
    blackWhiteImage($file, $i, $j, "twoColor", outFile:"./img_out", palette: $twitchColorPalette);
}

// rgb(174, 31, 207);
