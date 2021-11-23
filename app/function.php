<?php


function pre()
{
    $stack = debug_backtrace()[0];
    $file  = isset($stack['file']) ? $stack['file'] : '';
    $line  = isset($stack['line']) ? $stack['line'] : '';
    echo " \n file :$file ; line :$line ";
    dump(func_get_args());
    exit;
}

function pr()
{
    $stack = debug_backtrace()[0];
    $file  = isset($stack['file']) ? $stack['file'] : '';
    $line  = isset($stack['line']) ? $stack['line'] : '';
    echo " \n file :$file ; line :$line ";
    foreach (func_get_args() as $va) {
        dump($va);
    }

}

function ddclass()
{
    foreach (func_get_args() as $va) {
        dump(get_class($va));
    }
    exit;
}