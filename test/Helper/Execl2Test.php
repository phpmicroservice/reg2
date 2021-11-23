<?php


namespace HyperfTest\Helper;


use App\Queue\Data2Execl;
use App\Queue\RunData2Execl;
use HyperfTest\CliCase;

/**
 * Class ExeclTest
 * 转execl
 *
 * @package HyperfTest\Helper
 * @example composer test2 test/Helper/Execl2Test.php
 */
class Execl2Test extends CliCase
{

    public function testA()
    {
        $d = new RunData2Execl([
                                'rid' => 5978
                            ]);
        $d->run();
    }

}