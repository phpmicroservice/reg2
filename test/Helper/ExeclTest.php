<?php


namespace HyperfTest\Helper;


use App\Queue\Data2Execl;
use App\Queue\QueueBase;
use HyperfTest\CliCase;

/**
 * Class ExeclTest
 * 转execl
 *
 * @package HyperfTest\Helper
 * @example composer test2 test/Helper/ExeclTest.php
 */
class ExeclTest extends CliCase
{

    public function testA()
    {
        $d = new Data2Execl([
                                'bid' => '8230'
                            ]);
        $d->run();
    }

    public function testB()
    {
        $this->markTestSkipped('11');
        $d = new Data2Execl([
                                'bid' => '410'
                            ]);
        $d->run();
    }

}