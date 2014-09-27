<?php
namespace declarativeForms\fields;
use declarativeForms\validators;
use declarativeForms\types;

class Date extends Text {
    protected $type = 'date';
    protected $default_format = 'Y-m-d';
    protected $display_format = 'Y-m-d';
    public function __construct(array $attr=Array()) {
        $default_format = self::pop_arr_item($attr, 'default_format');
        $display_format = self::pop_arr_item($attr, 'display_format');
        if($default_format) {
            $this->default_format = $default_format;
        }
        if($display_format) {
            $this->display_format = $display_format;
        }
        parent::__construct($attr);
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
}