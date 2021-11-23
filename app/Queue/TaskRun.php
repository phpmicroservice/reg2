<?php


namespace App\Queue;


use App\Core\UidBind;
use App\Model\Bi\BiLineRun;
use App\Model\Bi\BiTaskRun;

/**
 * Class LineRun
 * 任务运行
 *
 * @package App\Queue
 *
 */
class TaskRun extends QueueBase
{

    use LineInquiryCreateTrait;

    public $trid;

    public function run()
    {
        $BiTaskRun = BiTaskRun::find($this->trid);
        if (!$BiTaskRun) {
            new \Error("不存在的运行记录");
        }
        $cate = $BiTaskRun->task_cate;
        if ($cate == 'line') {
            // 流水线任务
            return $this->runLine($BiTaskRun);
        } else {
            // 查询器任务
            return $this->runInquiry($BiTaskRun);
        }


    }

    /**
     * 运行一个流水线任务
     *
     * @param BiTaskRun $BiTaskRun
     */
    public function runLine(BiTaskRun $BiTaskRun)
    {
        $data  = [
            'line_id' => $BiTaskRun->task_cate_id,
            'user_id' => $BiTaskRun->user_id,
            'inquiry' => $BiTaskRun->task_parameter['inquiry'],
            'params'  => $BiTaskRun->task_parameter['params'],
        ];
        $model = new \App\Model\Bi\BiLineRun();
        $model->setData($data);
        $model->save();
        sleep(1);

        $this->queueAsync(\App\Queue\LineRun::class, [
            'rid' => $model->id
        ]);
        $loginfo                = (array)$BiTaskRun->loginfo;
        $loginfo[]              = [
            'r_id'=>$model->id
        ];
        $BiTaskRun->loginfo     = $loginfo;
        $BiTaskRun->contentinfo = [];
        $BiTaskRun->save();
    }

    /**
     * 执行一个 查询器任务
     *
     * @param BiTaskRun $BiTaskRun
     * @return bool
     * @throws \Exception
     */
    private function runInquiry(BiTaskRun $BiTaskRun)
    {
        $params      = $BiTaskRun->task_parameter;
        $inquiryName = $BiTaskRun->task_cate_id;

        $user_id     = $BiTaskRun->user_id;
        $QueueParams = new \App\Core\QueueParams($inquiryName, $params);
        $validation  = $QueueParams->getValidation();
        if ($validation->validate()->isFail()) {

            // 验证失败
            $info                   = [
                'inquiry'    => $inquiryName,
                'time'       => \Carbon\Carbon::now()->toString(),
                'params'     => $params,
                'validation' => [
                    'res'   => false,
                    'error' => $validation->firstError(),
                    'data'  => $validation->all()
                ]
            ];
            $loginfo                = (array)$BiTaskRun->loginfo;
            $loginfo[]              = $info;
            $BiTaskRun->loginfo     = $loginfo;
            $BiTaskRun->contentinfo = [];
            $BiTaskRun->save();
            // 验证失败，直接完成这个 流水线流程

            $this->end();

            return false;
        }

        $info                   = [
            'inquiry'    => $inquiryName,
            'params'     => $params,
            'time'       => \Carbon\Carbon::now()->toString(),
            'validation' => [
                'res'  => true,
                'data' => $validation->getSafeData()
            ]
        ];
        $loginfo                = (array)$BiTaskRun->loginfo;
        $loginfo[]              = $info;
        $BiTaskRun->loginfo     = $loginfo;
        $BiTaskRun->contentinfo = [];
        $BiTaskRun->save();
        // 验证通关，开始一个查询器，
        $this->queueAsync(InquiryCreate::class, [
            'name'    => $inquiryName,
            'params'  => $params,
            'rid'     => 0 ,
            'user_id' => $user_id,
            'li_id'   => 0
        ]);
        $this->end();

        return true;
    }

    public function end()
    {

    }

}