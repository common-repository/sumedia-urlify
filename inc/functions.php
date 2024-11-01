<?php

namespace Sumedia\Urlify;

const DS = DIRECTORY_SEPARATOR;

function ds($path)
{
    return str_replace('/', DS, $path);
}

function __($text) {
    return \__($text, SUMEDIA_URLIFY_PLUGIN_NAME);
}