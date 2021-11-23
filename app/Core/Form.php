<?php

namespace App\Core;

/**
 * Description of Form
 *
 * @author dongasai
 */
class Form
{

    private $list;

    public function add($name, \App\Form\FormBase$input)
    {
        $this->list[$name] = $input;
        return $this;
    }

    public function __serialize()
    {
        return $this->list;
    }

    public function __unserialize(array $data)
    {
        $this->list = $data;
    }

}
