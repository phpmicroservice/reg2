<?php


namespace App\Core;


interface InterfaceValidation
{

    public function rules(): array;
    public function translates();
}