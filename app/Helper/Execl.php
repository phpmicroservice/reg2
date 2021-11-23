<?php


namespace App\Helper;

use App\Model\Bi\BiInquiryDatum;
use App\Queue\Data2Execl;
use App\Queue\Data2ExeclBig;
use App\Queue\QueueBase;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Class Execl
 * Execl 助手
 *
 * @package App\Helper
 */
class Execl
{

    /**
     * 内容数组，写入execl 工作表
     *
     * @param Worksheet $Worksheet
     * @param $data
     * @param BiInquiryDatum $biData
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public static function array2Execl(Worksheet $Worksheet, $data, $biData, $type)
    {
        // 第一行，时间
        $Worksheet->getCellByColumnAndRow(1, 1)->setValue("查询创建时间");
        $Worksheet->getCellByColumnAndRow(2, 1)->setValue($biData->created_at);
        $Worksheet->getCellByColumnAndRow(3, 1)->setValue("查询完成时间");
        $Worksheet->getCellByColumnAndRow(4, 1)->setValue($biData->end_at);
        //第二行，数据行数
        $Worksheet->getCellByColumnAndRow(1, 2)->setValue("数据行数");
        $Worksheet->getCellByColumnAndRow(2, 2)->setValue($data['count'] ?? 0);



        // 往下是数据
        $arrayOld = $data['list'];
        if (is_array($arrayOld)) {
            reset($arrayOld);
            $k111 = key($arrayOld);
            foreach ($arrayOld as $k => $value) {
                if (is_array($value)) {
                    foreach ($value as $k2 => $v2) {
                        if (!isset($arrayOld[$k111][$k2])) {
                            $arrayOld[$k111][$k2] = null;
                        }
                    }
                }

            }
        }



        if (is_array($arrayOld)) {
            $keysvalue = array_shift($arrayOld);
            array_unshift($arrayOld, $keysvalue);


//            asort($keysvalue);
            if (is_array($keysvalue)) {
                $array = array(
                    'title' => array_keys((array)$keysvalue),
                    'value' => $arrayOld
                );
                // 累计判断
                $col = count($keysvalue);
                $countCell =$col * $data['count'];
                if ($countCell > 5000000) {
                    // 大约500万个单元格，不再生成表格
                    //第 3行，提示信息
                    $Worksheet->getCellByColumnAndRow(1, 3)->setValue("元素格式");
                    $Worksheet->getCellByColumnAndRow(2, 3)->setValue($countCell);
                    $Worksheet->getCellByColumnAndRow(3, 3)->setValue("行数");
                    $Worksheet->getCellByColumnAndRow(4, 3)->setValue($data['count']);
                    $Worksheet->getCellByColumnAndRow(5, 3)->setValue("列数");
                    $Worksheet->getCellByColumnAndRow(6, 3)->setValue($col);
                    if($type === 'inquiry'){

                    }
                    return true;
                }
            } else {
                // 单列，索引数组
                $newArray = [];
                foreach ($arrayOld as $k => $value) {
                    $newArray[] = [
                        'value' => $value
                    ];
                }
                $array = array(
                    'title' => array( 'value' ),
                    'value' => $newArray
                );
            }

            self::array2ExeclWrite($Worksheet, $array);
        } else {
            $Worksheet->getCellByColumnAndRow(1, 3)->setValue("数据内容");
            $Worksheet->getCellByColumnAndRow(2, 3)->setValue($data['list'] ?? '.null');
        }


    }

    /**
     * @param Worksheet $Worksheet
     * @param $array
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    private static function array2ExeclWrite(Worksheet $Worksheet, $array, $startRow = 3)
    {
        foreach (\__\__::get($array, 'title') as $key => $value) {
            $Worksheet->getCellByColumnAndRow($key + 1, $startRow)->setValue($value);

        }

        foreach (\__\__::get($array, 'value') as $kV => $value) {
            foreach (\__\__::get($array, 'title') as $keyT => $tV) {
                $re = $value[$tV] ?? null;
                $Worksheet->getCellByColumnAndRow($keyT + 1, $kV + $startRow + 1)->setValue($re);

            }
        }
    }

}