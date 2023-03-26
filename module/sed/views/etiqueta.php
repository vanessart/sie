<?php
if (!defined('ABSPATH'))
    exit;

function LoadPNG($imgname) 
{
    $im = @imagecreatefrompng($imgname); /* Attempt to open */
    if (!$im) { /* See if it failed */
        $im  = imagecreatetruecolor(150, 30); /* Create a blank image */
        $bgc = imagecolorallocate($im, 255, 255, 255);
        $tc  = imagecolorallocate($im, 0, 0, 0);
        imagefilledrectangle($im, 0, 0, 150, 30, $bgc);
        /* Output an errmsg */
        imagestring($im, 1, 5, 5, "Error loading $imgname", $tc);
    }
    return $im;
}
header("Content-Type: image/png");
$img = LoadPNG("bogus");
imagepng($img);