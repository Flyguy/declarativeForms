<?php
namespace declarativeForms\fields;

class Text extends Displayed {
    protected $type = 'text';

    public function toString() {
        return '<input type="'.$this->type.'" name="'.$this->name().'" id="'.$this->id().'" value="'.htmlspecialchars($this->data(true)).'"/>';
    }
}