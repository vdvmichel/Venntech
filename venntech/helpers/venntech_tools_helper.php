<?php

defined('BASEPATH') or exit('No direct script access allowed');

function isNotEmpty($value)
{
    if (isset($value) && $value != '') {
        return true;
    }
    return false;
}

function venntech_toPlainArray($arr)
{
    return '<pre>' . print_r($arr, true) . '</pre>';
}

function array_get_by_index($index, $array)
{

    $i = 0;
    foreach ($array as $value) {
        if ($i == $index) {
            return $value;
        }
        $i++;
    }
    // may be $index exceedes size of $array. In this case NULL is returned.
    return NULL;
}