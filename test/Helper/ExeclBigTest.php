<?php


namespace HyperfTest\Helper;


use App\Queue\Data2Execl;
use App\Queue\Data2ExeclBig;
use App\Queue\QueueBase;
use HyperfTest\CliCase;

/**
 * Class ExeclTest
 * 转execl
 *
 * @package HyperfTest\Helper
 * @example composer test2 test/Helper/ExeclBigTest.php
 */
class ExeclBigTest extends CliCase
{

    public function testA()
    {
        $d = new Data2ExeclBig([
                                'bid' => '5978'
                            ]);
        $d->run();
    }




}