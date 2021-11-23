<?php


namespace App\Queue;

use App\Core\Cache;
use App\Core\Logger;
use App\Helper\Demo;
use App\Helper\Execl;
use App\Helper\Sender;
use App\Model\Bi\BiInquiryDatum;
use App\Model\Bi\BiLineRun;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Class Data2Execl
 * 流水线查询结果转 execl
 *
 * @package App\Queue
 * @extends
 */
class RunData2Execl extends QueueBase
{

    public  $rid;
    private $dir;

    public function init()
    {
        $dir       = BASE_PATH . '/runtime/execl/';
        $this->dir = $dir;
    }

    public function run()
    {
        $lineRun = BiLineRun::find($this->rid);
        if (empty($lineRun)) {
            throw new \Error("不存在的数据");
        }
        try {
            $this->run2($lineRun);
            $this->notice($lineRun);
        } catch (\Exception $exception) {
            $this->noticeError($lineRun, $exception);
        }

        // 设置缓存过期
        $key = "rid:{$this->rid}:lock";
        Cache::set($key, true, 0);
        return true;
    }

    private function run2($lineRun)
    {

        $name = $this->getName($lineRun);
        mkdir(dirname($name), 0777, true);
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        /**
         * @var $listData BiInquiryDatum[]
         */
        $listData = BiInquiryDatum::where('rid', $this->rid)->with([ 'linei' ])->get();

//        Demo::randEx(98);

        // 参数储存
        $worksheet = $spreadsheet->getSheet(0);
        $worksheet->setTitle("参数");
        $worksheet->setTitle('params');
        $index = 1;
        $worksheet->getCellByColumnAndRow(1, $index)->setValue("参数名字");
        $worksheet->getCellByColumnAndRow(2, $index)->setValue('参数内容');
        $index++;

        foreach ($lineRun->params as $k => $va) {
            $worksheet->getCellByColumnAndRow(1, $index)->setValue($k);
            $worksheet->getCellByColumnAndRow(2, $index)->setValue(print_r($va, true));
            $index++;
        }

        $contentinfo = $lineRun->contentinfo;
        $limit       = $listData[0];
        if ($limit->inquiry == 'LineExeclLimit') {
            // 存在限制器
            $listData = $this->LineExeclLimit($limit, $listData);
        }

        foreach ($listData as $k => $biData) {
            $index     = $k + 1;
            $worksheet = $spreadsheet->createSheet($index);
            $content   = json_decode($biData->contentinfo, true);
            $worksheet->setCodeName($biData->inquiry . '_' . $biData->id);
            $worksheet->setTitle($biData->linei->title . '_' . $biData->li_id);
            Execl::array2Execl($worksheet, $content, $biData,'line');
        }


        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($name);

        $contentinfo['size']  = filesize($name);
        $contentinfo['file']  = $name;
        $lineRun->contentinfo = $contentinfo;
        $lineRun->save();

    }

    /**
     * @param BiInquiryDatum $limit
     * @param BiInquiryDatum[] $listData
     * @return  BiInquiryDatum[]
     */
    private function LineExeclLimit(BiInquiryDatum $limit, \Hyperf\Database\Model\Collection $listData)
    {

        $contentinfo = json_decode($limit->contentinfo, true);
        $bool        = $contentinfo['list']['limitBool'] ?? false;
        $limitIn     = $contentinfo['list']['limitIn'] ?? [];
        $limitNot    = $contentinfo['list']['limitNot'] ?? [];
        if ($bool && $limitIn) {
            $res = [];
            foreach ($listData as $datum) {
                if (in_array($datum->li_id, $limitIn)) {
                    $res[] = $datum;
                }
            }

            return $res;
        }
        if ($bool && $limitNot) {
            $res = [];
            foreach ($listData as $datum) {
                if (!in_array($datum->li_id, $limitIn)) {
                    $res[] = $datum;
                }
            }

            return $res;
        }

        return $listData;

    }

    private function getName(BiLineRun $run)
    {
        $time     = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $run->created_at);
        $title    = $run->line->title;
        $fileName = $time->format("Y/m/d/") . $run->id . '.' . $title . '.xlsx';

        return $this->dir . $fileName;
    }

    /**
     * 流水线运行完成通知
     *
     * @param int $fd
     * @param \App\Model\Bi\BiLineRun $lineRunInfo
     */
    public function notice(\App\Model\Bi\BiLineRun $lineRunInfo)
    {
        $data = [
            'rid'     => $lineRunInfo->id,
            'line_id' => $lineRunInfo->line_id
        ];

        Sender::send2uid($lineRunInfo->user_id, 'sys', 'linerun_execlok', $data);
    }

    /**
     * 错误通知
     */
    public function noticeError(\App\Model\Bi\BiLineRun $lineRunInfo, \Exception $exception)
    {

        $data = [
            'rid'     => $lineRunInfo->id,
            'line_id' => $lineRunInfo->line_id,
            'res'     => false,
            'message' => $exception->getMessage(),
            'trace'   => $exception->getTrace(),
        ];

        Sender::send2uid($lineRunInfo->user_id, 'sys', 'linerun_execlerror', $data);

    }

}