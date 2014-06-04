<?php
namespace declarativeForms\fields;
use declarativeForms\validators;

class DateTime extends Date {
    protected $type = 'datetime';
    protected $default_format = DateTime::ISO8601;
    protected $display_format = DateTime::ISO8601;

    protected function  assign_standard_validators() {
        return array(
            validators::is_datetime($this->default_format)
        );
    }
}