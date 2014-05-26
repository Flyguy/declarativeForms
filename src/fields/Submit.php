<?php
namespace declarativeForms\fields;

class Submit extends Text {
    protected $type = 'submit';
    public function toString() {
        return '<input type="'.$this->type.'" value="'.$this->label().'"/>';
    }
}