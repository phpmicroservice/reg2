<?php


namespace App\Queue;

use App\Inquiry\Helper\Sql;
use App\Service\QueueService;
use Hyperf\AsyncQueue\Job;

/**
 * Class QueueBase
 * 异步任务基类
 *
 * @package App\Queue
 */
abstract class QueueBase extends Job implements QueueInterface
{

    public function __construct($params)
    {
        pr($params);
        foreach ($params as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
        if (method_exists($this, 'init')) {
            $this->init();
        }
    }

    public function handle()
    {
        pr(static::class);
//        try {
        $data = $this->run();
//        } catch (\Error $error) {
//            $data = $error->getMessage();
//        } catch (\Exception $exception) {
//            $data = $exception->getMessage();
//        }
//       pre($data);
        return $data;

    }

    /**
     * 异步队列任务
     *
     * @param $name
     * @param $params
     */
    static public function queueAsync($name, $params)
    {
        $container = \Hyperf\Utils\ApplicationContext::getContainer();
        $service   = $container->get(QueueService::class);

        $service->pushJob($name, $params);
    }


}