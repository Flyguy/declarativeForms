<?php
namespace declarativeForms\fields;


abstract class Displayed extends Base {
    protected $label;
    protected $hint;
    public function __construct($default=null, array $validators=Array(), $label=null, $hint=null, $extra=null) {
        $this->label = $label;
        $this->hint  = $hint;
        parent::__construct($default, $validators, $extra=null);
    }

    public function generateLabel($name) {
        return ucwords(trim(strtolower(str_replace(array('-','_','.'),' ',preg_replace('/(?<![A-Z])[A-Z]/', ' \0', $name)))));
    }

    public function label() {
        $this->check_bound();
        return ($this->label ? $this->label: $this->generateLabel($this->name));
    }

    public function hint() {
        return $this->hint;
    }

    public function is_hidden() {
        return false;
    }

    public static function create(array $attributes = array()) {
        return new static(
                self::pop_arr_item($attributes, 'default'),
                self::pop_arr_item($attributes, 'validators', Array()),
                self::pop_arr_item($attributes, 'label'),
                self::pop_arr_item($attributes, 'hint'),
                $attributes
        );
    }
}