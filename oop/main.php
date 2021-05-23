<?php
//require "twoColorFilter.php";
require "Color.php";
require "fourColorFilter.php";
require_once "twoColorsPalette.php";
require_once "fourColorPalette.php";
require_once "twoColorFilter.php";

$black = new Color(0,0,0);
$grey = new Color(85,85,85);
$lightGrey = new Color(170, 170, 170);
$white = new Color(255,255,255);
$darkPurple = new Color(95, 9, 115);
$lightPurple = new Color(168, 109, 162);

$twitchColorPalette = new twoColorsPalette($darkPurple, $white);
$twitchFourColor = new fourColorsPalette($black, $darkPurple, $lightPurple, $white);

$filter = new fourColorFilter("../images/out1.jpg", 2,2,".", $twitchFourColor);
$filter->run();

$anotherFilter = new twoColorFilter('../images/out1.jpg', 2, ".", $twitchColorPalette);
$anotherFilter->run();
