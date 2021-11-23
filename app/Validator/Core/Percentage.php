<?php


namespace App\Validator\Core;


use Inhere\Validate\Validator\AbstractValidator;

/**
 * Class Percentage
 * 百分比验证，最小1 最大 100
 *
 * @package App\Validator\Core
 */
class Percentage extends AbstractValidator
{

    public function validate($value, $data): bool
    {
        if ($value < 0.01 || $value > 100) {
            return false;
        }

        return true;

    }

}