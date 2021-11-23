<?php

namespace App\Helper;

class Trace
{

    static public function getRunInfo()
    {
        return [
            'mem'     => memory_get_usage() / 1024 / 1024,
            'max_mem' => memory_get_peak_usage() / 1024 / 1024,
            'pid'     => getmypid()
        ];
    }

}