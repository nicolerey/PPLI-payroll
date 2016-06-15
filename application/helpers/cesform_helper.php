<?php

if (!function_exists('preset')) {

    function preset($arr, $key, $default = FALSE) {
        return isset($arr[$key]) ? $arr[$key] : $default;
    }

}