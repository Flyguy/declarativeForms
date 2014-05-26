<?php
namespace declarativeForms\fields;


class Hidden extends Base {
    public function is_hidden() {
        return true;
    }

    public function toString() {
        return '<input type="hidden" name="'.$this->name().'" id="'.$this->id().'" value="'.htmlspecialchars($this->data(true)).'"/>';
    }
}