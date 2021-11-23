<?php


namespace App\Core;


use App\Inquiry\InquiryInterface;
use App\Inquiry\Node\NodeBase;
use App\Inquiry\Pay\TTypeCount;
use Inhere\Validate\Filter\Filters;
use Inhere\Validate\Validator\AbstractValidator;

/**
 * 验证组件的再封装.
 */
abstract class Validation extends \Inhere\Validate\Validation implements InterfaceValidation
{

    /**
     * @param array $data
     * @param array $rules
     * @param array $translates
     * @param string $scene
     * @param bool $startValidate Start verification now
     *
     * @throws InvalidArgumentException
     */
    public function __construct(
        array $data = [],
        array $rules = [],
        array $translates = [],
        string $scene = '',
        bool $startValidate = false
    )
    {
        $this->data = $data;

        $this->atScene($scene)->setRules($rules)->setTranslates($translates);
        if (method_exists($this, 'init')) {
            $this->init();
        }
        if ($startValidate) {
            $this->validate();
        }
    }

    public function init()
    {

    }

    public function node2rule($nodes)
    {
        $rules = [];
        /**
         * @var NodeBase[] $nodes
         */
        $fieldTrans = [];
        foreach ($nodes as $node) {
            if ($node->in) {
                $rules2 = $node->getValivation();
                if ($rules2) {
                    $rules = array_merge($rules, $rules2);
                }
                $fieldTrans = array_merge($fieldTrans, $node->getTran());
            }

        }
        $this->setRules($rules);
        $this->setTranslates($fieldTrans);

    }

    /**
     * 收集可用的规则
     *
     * @return array
     */
    public function getCanRules(): array
    {
        $this->prepareValidation();
        $rules = [];

        foreach ($this->collectRules() as $fields => $rule) {

            $fields    = is_string($fields) ? Filters::explode($fields) : (array)$fields;
            $validator = $rule[0];
            if ($validator instanceof AbstractValidator) {
                $validator = get_class($validator);
            }
            $type    = $rule['type'] ?? "";
            $options = $rule['options'] ?? [];
            foreach ($fields as $field) {
                if (isset($rules[$field])) {
                    $rules[$field]['rule'][] = $validator;
                } else {
                    $rules[$field]['rule'][] = $validator;
                }
                if (isset($rule['default'])) {
                    $rules[$field]['default'] = $rule['default'];
                }
                $rules[$field]['attr']    = $this->getTranslate($field);
                $rules[$field]['type']    = $type;
                $rules[$field]['name']    = $field;
                $rules[$field]['options'] = $options;
            }
        }
        dump($rules);
        return $this->prepareFromType($rules);
    }

    /**
     * 处理表单类型
     *
     * @param $rules
     * @return array
     */
    protected function prepareFromType(array $rules): array
    {
        foreach ($rules as $k => &$rule) {
            if (array_intersect([ 'number', 'int' ], $rule['rule'])) {
                // 数字 表单
                $rule['rule'] = array_diff($rule['rule'], [ 'number' ]);
                $rule['type'] = 'number';
                $rule['name'] = $k;
            }
            if (array_intersect([ 'date', 'dateFormat' ], $rule['rule'])) {
                // 日期 表单
                $rule['rule'] = array_diff($rule['rule'], [ 'number' ]);
                $rule['type'] = 'date';
                $rule['name'] = $k;
            }
            if (array_intersect([ 'bool' ], $rule['rule'])) {
                // switch 表单
                $rule['type'] = 'bool';
            }
            if (array_intersect([ 'array' ], $rule['rule'])) {
                // switch 表单
                $rule['type'] = 'in';
            }
            if (array_intersect([ 'enum' ], $rule['rule'])) {
                // switch 表单
                $rule['type'] = 'enum';
            }
            if (array_intersect([ 'App\Core\Validator\Checked' ], $rule['rule'])) {
                // switch 表单
                $rule['type'] = 'checked';
            }
            if (array_intersect([ 'string' ,'float'], $rule['rule'])) {
                // switch 表单
                $rule['type'] = 'string';
            }
            // uins
        }

        return $rules;;
    }

    public function isEmpty($val)
    {
        pr(func_get_args());

        return empty($val);
    }

}