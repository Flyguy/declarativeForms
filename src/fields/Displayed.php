<?php
namespace declarativeForms\fields;


abstract class Displayed extends Base {
    protected $label;
    protected $hint;
    public function __construct(array $attr=Array()) {
        $this->label = self::pop_arr_item($attr, 'label');
        $this->hint  = self::pop_arr_item($attr, 'hint');
        parent::__construct($attr);
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
}