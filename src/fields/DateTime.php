<?php
namespace declarativeForms\fields;
use declarativeForms\validators, declarativeForms\types;

class DateTime extends Date {
    protected $type = 'datetime';
    protected $default_format = \DateTime::ISO8601;
    protected $display_format = \DateTime::ISO8601;

    public function process_data($data) {
        if($data) {
            $data = types\DateTime::createFromFormat($this->display_format, $data);
            if(!$data) {
                return null;
            }
            $data->set_default_format($this->default_format);
        }
        return $data;
    }

    protected function process_default($value) {
        if(!$value instanceof \DateTime) {
            $datetime = types\DateTime::createFromFormat($this->default_format, $value);
        } else {
            $datetime = $value;
        }
        if($datetime) {
            return $datetime->format($this->display_format);
        }
        return $value;
    }

    protected function  assign_standard_validators() {
        return array(
            validators::is_datetime($this->default_format)
        );
    }
}