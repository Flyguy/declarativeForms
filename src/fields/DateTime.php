<?php
namespace declarativeForms\fields;
use declarativeForms\validators;

class DateTime extends Date {
    protected $type = 'datetime';
    protected $format = 'Y-m-d H:i:s';

    protected function  assign_standard_validators() {
        return array(
            validators::is_datetime($this->format)
        );
    }
}