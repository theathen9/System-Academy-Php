<?php

function post($key, $default = null)
{
    return $_POST[$key] ?? $default;
}

function get($key, $default = null)
{
    return $_GET[$key] ?? $default;
}