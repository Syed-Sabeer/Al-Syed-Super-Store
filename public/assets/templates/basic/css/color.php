<?php
header("Content-Type:text/css");
function checkHexColor($color)
{
    return preg_match('/^#[a-f0-9]{6}$/i', $color);
}
if (isset($_GET['color']) and $_GET['color'] != '') {
    $color = "#" . $_GET['color'];
}
if (!$color or !checkHexColor($color)) {
    $color = "#336699";
}

function hexToHsl($hex)
{
    $hex   = str_replace('#', '', $hex);
    $red   = hexdec(substr($hex, 0, 2)) / 255;
    $green = hexdec(substr($hex, 2, 2)) / 255;
    $blue  = hexdec(substr($hex, 4, 2)) / 255;
    $cmin  = min($red, $green, $blue);
    $cmax  = max($red, $green, $blue);
    $delta = $cmax - $cmin;
    if ($delta == 0) {
        $hue = 0;
    } elseif ($cmax === $red) {
        $hue = (($green - $blue) / $delta);
    } elseif ($cmax === $green) {
        $hue = ($blue - $red) / $delta + 2;
    } else {
        $hue = ($red - $green) / $delta + 4;
    }
    $hue = round($hue * 60);
    if ($hue < 0) {
        $hue += 360;
    }
    $lightness  = (($cmax + $cmin) / 2);
    $saturation = $delta === 0 ? 0 : ($delta / (1 - abs(2 * $lightness - 1)));
    if ($saturation < 0) {
        $saturation += 1;
    }
    $lightness  = round($lightness * 100);
    $saturation = round($saturation * 100);
    $hsl['h']   = $hue;
    $hsl['s']   = $saturation;
    $hsl['l']   = $lightness;
    return $hsl;
}
?>

:root{
--base-h: <?php echo hexToHsl($color)['h']; ?>;
--base-s: <?php echo hexToHsl($color)['s']; ?>%;
--base-l: <?php echo hexToHsl($color)['l']; ?>%;
}