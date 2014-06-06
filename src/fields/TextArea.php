<?php
namespace declarativeForms\fields;

class TextArea extends Displayed {
    public function toString(array $custom_props = array()) {
        $args = Array(
            'name' => $this->name(),
            'id' => $this->id(),
        );
        return '<textarea '.static::render_attributes($args+$custom_props).'>'.htmlspecialchars($this->form_data(true)).'</textarea>';
    }
};