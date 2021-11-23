<?php


namespace App\Core\Validator;


use Inhere\Validate\Validator\AbstractValidator;

/**
 * Class Checked
 * 多选验证
 * @package App\Core\Validator
 */
class Checked extends AbstractValidator
{

    public function validate($value, $data): bool
    {

        return true;
    }

}