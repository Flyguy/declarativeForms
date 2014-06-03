<?php
namespace declarativeForms\fields;
use declarativeForms\validators;

class DateTime extends Date {
    protected $type = 'datetime';
    protected $format = '';

    protected function  assign_standard_validators() {
        return array(

        );
    }
}