<?php
//require "twoColorFilter.php";
require "Color.php";
require "colorPalette.php";
require "./fourColorFilter.php";

$black = new Color(0,0,0);
$grey = new Color(85,85,85);
$lightGrey = new Color(170, 170, 170);
$white = new Color(255,255,255);
$darkPurple = new Color(95, 9, 115);
$lightPurple = new Color(168, 109, 162);

$twitchColorPalette = new twoColorsPalette($darkPurple, $white);
$twitchFourColor = new fourColorsPalette($black, $darkPurple, $lightPurple, $white);

$filter = new fourColorFilter("./11.png", 2,2,".", $twitchFourColor);
$filter->run();

