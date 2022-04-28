<?php
//  Include auto loader
include '../vendor/autoload.php';

//  PHP settings
ini_set("max_execution_time", "-1");
ini_set("memory_limit", "-1");
ignore_user_abort(true);
set_time_limit(0);

//  App settings
define('EOL', php_sapi_name() === 'cli' ? chr(10) . chr(13) : '<br/>');

//  Convert size from bytes to human readable format
function convertBytes($size): string
{
    $unit = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
    return round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . $unit[$i];
}

function convertTime($time): string
{
    return round($time / 1000000000, 4) . 's';
}

function stopWatch($time): string
{
    return convertTime(hrtime(true) - $time);
}
