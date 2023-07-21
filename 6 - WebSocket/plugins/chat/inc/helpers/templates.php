<?php
function get_chat_template_part($template_name, $args = []) {
    $template_path = CHAT_ROOT . 'inc/template-parts/'. $template_name .'.php';

    if(!file_exists($template_path)) {
        return false;
    }

    ob_start();

    include $template_path;

    $template_content = ob_get_clean();

    return $template_content;
}

function number_to_color($number) {
    $maxValue = 255;

    $hueStart = 0;
    $hueStep = 300;

    $hue = fmod($hueStart + $number * $hueStep, 360);

    $h = $hue / 60;
    $c = $maxValue;
    $x = $c * (1 - abs(fmod($h, 2) - 1));

    if ($h >= 0 && $h < 1) {
        list($r, $g, $b) = [$c, $x, 0];
    } elseif ($h >= 1 && $h < 2) {
        list($r, $g, $b) = [$x, $c, 0];
    } elseif ($h >= 2 && $h < 3) {
        list($r, $g, $b) = [0, $c, $x];
    } elseif ($h >= 3 && $h < 4) {
        list($r, $g, $b) = [0, $x, $c];
    } elseif ($h >= 4 && $h < 5) {
        list($r, $g, $b) = [$x, 0, $c];
    } else {
        list($r, $g, $b) = [$c, 0, $x];
    }

    $r = dechex(round($r));
    $g = dechex(round($g));
    $b = dechex(round($b));

    $r = str_pad($r, 2, '0', STR_PAD_LEFT);
    $g = str_pad($g, 2, '0', STR_PAD_LEFT);
    $b = str_pad($b, 2, '0', STR_PAD_LEFT);

    // HEX color
    $color = "#" . $r . $g . $b;

    return $color;
}