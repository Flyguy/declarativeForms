<?php
namespace declarativeForms\fields;
use declarativeForms\validators;
use declarativeForms\types;

class Date extends Text {
    protected $type = 'date';
    protected $default_format = 'Y-m-d';
    protected $display_format = 'd-m-Y';
    public function __construct($default=null, array $validators=Array(), $label=null, $hint=null, $default_format=null, $display_format=null, array $extra=Array()) {
        if($default_format) {
            $this->default_format = $default_format;
        }
        parent::__construct($default, $validators, $label, $hint, $extra);
    }

    protected function process_default($value) {
        if(!$value instanceof \DateTime) {
            $datetime = types\Date::createFromFormat($this->default_format, $value);
        } else {
            $datetime = $value;
        }
        if($datetime) {
            return $datetime->format($this->display_format);
        }
        return $value;
    }

    public function get_default_format() {
        return $this->default_format;
    }

    public function get_display_format() {
        return $this->display_format;
    }

    public function process_data($data) {
        if($data) {
            $data = types\Date::createFromFormat($this->display_format, $data);
            if(!$data) {
                return null;
            }
            $data->set_default_format($this->default_format);
        }
        return $data;
    }

    protected function assign_standard_validators() {
        return Array(
            validators::is_date($this->display_format)
        );
    }

    public static function create(array $attributes = array()) {
        return new static(
                self::pop_arr_item($attributes, 'default'),
                self::pop_arr_item($attributes, 'validators', Array()),
                self::pop_arr_item($attributes, 'label'),
                self::pop_arr_item($attributes, 'hint'),
                self::pop_arr_item($attributes, 'default_format'),
                self::pop_arr_item($attributes, 'display_format'),
                $attributes
        );
    }
}