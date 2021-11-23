<?php


namespace App\Queue;

use App\Helper\ArrayUtil;
use App\Helper\Execl;
use App\Model\Bi\BiInquiryDatum;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Class Data2ExeclBig
 * 查询结果转 execl 大量数据的转换，分批次
 *
 * @package App\Queue
 */
class Data2ExeclBig extends QueueBase
{

    public         $bid;
    private static $dir = BASE_PATH . '/runtime/execl/';


    public function run()
    {
        $biData = BiInquiryDatum::find($this->bid);
        if (empty($biData)) {
            throw new \Error("不存在的数据");
        }

        $name  = $this->getName($biData);
        $title = $biData->inquiry . '_' . $biData->id;

        self::BiData2Execl($biData, $name, $title, 'inquiryBig');

        $contentinfo         = json_decode($biData->contentinfo, true);
        $contentinfo['size'] = filesize($name);
        $contentinfo['file'] = $name;
        $biData->contentinfo = json_encode($contentinfo);
        $biData->save();

        return true;
    }

    /**
     * 数据生成 Execl
     *
     * @param $contentinfo
     * @param $biData
     * @param $name
     * @param $title
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public static function BiData2Execl($biData, $name, $title, $type = 'inquiry')
    {
        $contentinfo = json_decode($biData->contentinfo, true);
        $list        = $contentinfo['list'];
        unset($contentinfo['list']);
        mkdir(dirname($name), 0777, true);
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $worksheet   = $spreadsheet->getActiveSheet();

        $keys = $contentinfo['keys'];
        $contentinfo['list'] = $keys;
        Execl::array2Execl($worksheet, $contentinfo, $biData, $type);
        $invalidCharacters = $worksheet->getInvalidCharacters();
        $title             = str_replace($invalidCharacters, '', $title);
        $worksheet->setTitle($title );
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($name);
        // 分列，50 列一次
        $jixu = true;
        $k    = 0;
        while ($jixu) {
            $index = $k + 1;
            dump(memory_get_usage() / 1024 / 1024);

            $reader      = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            $spreadsheet = $reader->load($name);


            $worksheet = $spreadsheet->createSheet($index);
            $keys2     = array_splice($keys, 0, 20);
            $keys2[]   = 'user_id';
            $list2     = ArrayUtil::array_columns($list, implode(',', $keys2));

            $contentinfo['list'] = $list2;
            Execl::array2Execl($worksheet, $contentinfo, $biData, $type);
            $invalidCharacters = $worksheet->getInvalidCharacters();
            $title             = str_replace($invalidCharacters, '', $title);
            $worksheet->setTitle($title . '-' . $index);
            $k++;
            // XLSX
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save($name);
            dump(memory_get_usage() / 1024 / 1024);
            unset($writer);
            unset($worksheet);
            unset($spreadsheet);
            unset($reader);
            gc_collect_cycles();
        }


    }

    /**
     * 获取文件名字
     *
     * @param BiInquiryDatum $biData
     * @return string
     */
    static public function getName(BiInquiryDatum $biData)
    {
        $time     = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $biData->created_at);
        $inquiry  = $biData->inquiry;
        $fileName = $time->format("Y/m/d/") . $biData->id . $inquiry . '.xlsx';

        return self::$dir . $fileName;
    }


}