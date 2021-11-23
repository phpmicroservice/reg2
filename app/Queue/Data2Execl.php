<?php


namespace App\Queue;

use App\Helper\Execl;
use App\Model\Bi\BiInquiryDatum;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Class Data2Execl
 * 查询结果转 execl
 *
 * @package App\Queue
 */
class Data2Execl extends QueueBase
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
        self::BiData2Execl($biData, $name, $title,'inquiry');
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
    public static function BiData2Execl($biData, $name, $title,$type ='inquiry')
    {
        $contentinfo = json_decode($biData->contentinfo, true);

        mkdir(dirname($name), 0777, true);
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $worksheet   = $spreadsheet->getActiveSheet();
        Execl::array2Execl($worksheet, $contentinfo, $biData,$type);
        $invalidCharacters = $worksheet->getInvalidCharacters();
        $title = str_replace($invalidCharacters, '', $title);
        $worksheet->setTitle($title);
        // XLSX
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($name);
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