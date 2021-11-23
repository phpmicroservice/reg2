<?php


namespace App\Queue;


use App\Core\UidBind;

/**
 * Class CleanCache
 * 清理缓存异步任务
 *
 * @package App\Queue
 */
class CleanCache extends QueueBase
{

    public function run()
    {
        $rootPath = UidBind::cacheDir();

        return true;
    }

}