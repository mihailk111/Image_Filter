<?php
//require "twoColorFilter.php";
require "Color.php";
require "colorPalette.php";

$black = new Color(0,0,0);
$grey = new Color(85,85,85);
$lightGrey = new Color(170, 170, 170);
$white = new Color(255,255,255);
$darkPurple = new Color(95, 9, 115);

$twitchColorPalette = new twoColorsPalette($darkPurple, $white);

$filter = new twoColorFilter("./11.png", 2,".", $twitchColorPalette);
$filter->run();

