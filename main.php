<?php

spl_autoload_register(function (string $className) {
    require_once $className . ".php";
});


$black = S('Color',0,0,0);

$grey = new Color(85,85,85);
$lightGrey = new Color(170, 170, 170);
$white = new Color(255,255,255);
$darkPurple = new Color(95, 9, 115);
$lightPurple = new Color(168, 109, 162);

$twitchColorPalette = new twoColorsPalette($darkPurple, $white);
$twitchFourColor = new fourColorsPalette($black, $darkPurple, $lightPurple, $white);

$filter = new fourColorsFilter("../images/out1.jpg", 2,2,".", $twitchFourColor);
$filter->run();

$anotherFilter = new twoColorFilter('../images/out1.jpg', 2, ".", $twitchColorPalette);
$anotherFilter->run();

function S(string $class, ...$args)
{
    return new $class(...$args);
}