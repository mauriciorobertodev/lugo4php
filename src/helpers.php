<?php

if (!function_exists('dd')) {
    function dd(...$vars)
    {
        foreach ($vars as $var) {
            var_dump($var);
        }
        die();
    }
}

if (!function_exists('dump')) {
    function dump(...$vars)
    {
        foreach ($vars as $var) {
            var_dump($var);
        }
    }
}

if (!function_exists('benchmark')) {
    function benchmark($start = null)
    {
        if ($start === null) {
            return microtime(true);
        }
        return round(microtime(true) - $start, 2) . ' seconds';
    }
}


