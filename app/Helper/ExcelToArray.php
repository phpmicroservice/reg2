<?php

namespace App\Helper;


use PhpOffice\PhpSpreadsheet\Reader\Exception;

/**
 * xlsx文件转成php数组文件
 * Class ExcelToArray
 * @package App\Helper
 */
class ExcelToArray
{
    /**
     * 获取xlsx文件内容
     * @param string|null $filename 文件名称
     * @param int $startRow 开始行数
     * @param int $startColumn 开始列数
     * @param int $primaryKeyColumn 主键列(会以主键组合数据)
     * @return array
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws Exception
     */
    public function exec(string $filename, int $startRow = 3, int $startColumn = 1, int $primaryKeyColumn = 1): array
    {
        if (!file_exists($filename)) {
            echo "----{$filename} File not found" . PHP_EOL;
            return [];
        }
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
        $reader->setReadDataOnly(TRUE);
        $spreadsheet = $reader->load($filename); //载入excel表格
        $worksheet = $spreadsheet->getActiveSheet();
        $highestRow = $worksheet->getHighestRow(); // 总行数
        $highestColumn = $worksheet->getHighestColumn(); // 最后一列名
        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // 获取列总数
        $lines = $highestRow - $startRow;
        if (!$lines) {
            echo __FUNCTION__ . "----{$filename} ----Data is empty!" . PHP_EOL;
            return [];
        }
        $data = [];
        $header = [];
        for ($row = $startRow; $row <= $highestRow; $row++) {
            for ($column = $startColumn; $column < $highestColumnIndex; $column++) {
                $val = $worksheet->getCellByColumnAndRow($column, $row)->getValue();
                if ($row == $startRow) {
                    // 获取列名
                    array_push($header, $val);
                } else {
                    // 以primaryKey和列名组装数据
                    $cName = $header[$column - $startColumn]; // 获取列名
                    $itemId = substr($cName,0,-3) ;
                    if(substr($cName,-3) !='sum' || !is_numeric($itemId)){
                        continue;
                    }
                    if(!$val){
                        continue;
                    }
                    $primaryKey = $worksheet->getCellByColumnAndRow($primaryKeyColumn, $row)->getValue(); // 获取primaryKey
                    $data[$primaryKey][$itemId] = $val;
                }
            }
        }
        return $data;
    }

    /**
     * 生成数据文件
     * @param array $data 数组数据
     * @param string $fileName 文件名
     * @return false|string
     */
    public function product(array $data, string $fileName = "../../data.php")
    {
        if (empty($data)) {
            // 数据为空
            echo __FUNCTION__ . "----Data is empty!" . PHP_EOL;
            return false;
        }
        if (file_exists($fileName)) {
            // 文件已存在
            echo __FUNCTION__ . "----{$fileName}----File already exists!" . PHP_EOL;
            return false;
        }
        $text = '<?php $data=' . var_export($data, true) . ';';

        if (false !== fopen($fileName, 'w+')) {
            $write = file_put_contents($fileName, $text);
            if ($write) {
                echo __FUNCTION__ . "----File generation succeeded!----{$fileName}" . PHP_EOL;
                chmod($fileName, '0755');
                return $fileName;
            }
            echo __FUNCTION__ . "----File generation failure!----{$fileName}" . PHP_EOL;
        } else {
            echo __FUNCTION__ . "----File generation succeeded!----{$fileName}" . PHP_EOL;
        }
        return false;
    }
}
