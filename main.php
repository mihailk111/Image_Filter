<?php

require_once "./vendor/autoload.php";
require_once "./blackWhiteImage.php";
require_once "./colorPalette.php";

use Symfony\Component\Finder\Finder;

$black = new Color(0,0,0);
$grey = new Color(85,85,85);
$lightGrey = new Color(170, 170, 170);
$white = new Color(255,255,255);

$darkPurple = new Color(95, 9, 115);
$twitchColorPalette = new twoColorsPalette($darkPurple, $white);


$i = 2;
$j = 0;

$finder = new Finder();

foreach ($finder->in("images")->files()->name("*.jpg") as $file )
{
    echo $file->getRealPath() . "\n";
    blackWhiteImage("images/".$file->getRelativePathName(), $i, $j, "twoColor", outDir:"img_out", palette: $twitchColorPalette);
}



