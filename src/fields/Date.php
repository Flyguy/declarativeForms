<?php
namespace declarativeForms\fields;
use declarativeForms\validators;
use declarativeForms\types\DateTime;

class Date extends Text {
    protected $type = 'date';
    protected $format = 'Y-m-d';
    public function __construct($default=null, array $validators=Array(), $label=null, $hint=null, $format=null, array $extra=Array()) {
        if($format) {
            $this->format = $format;
        }
        parent::__construct($default, $validators, $label, $hint, $extra);
    }

    public function get_format() {
        return $this->format;
    }

    public function data_as_datetime($default=false) {
        if($data = $this->data($default)) {
            return DateTime::createFromFormat($this->format, $data);
        }
        return NULL;
    }

    protected function assign_standard_validators() {
        return Array(
            validators::is_date($this->format)
        );
    }

    public static function create(array $attributes = array()) {
        return new static(
                self::pop_arr_item($attributes, 'default'),
                self::pop_arr_item($attributes, 'validators', Array()),
                self::pop_arr_item($attributes, 'label'),
                self::pop_arr_item($attributes, 'hint'),
                self::pop_arr_item($attributes, 'format'),
                $attributes
        );
    }
}