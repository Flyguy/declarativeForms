<?php
namespace declarativeForms\fields;

class TextArea extends Displayed {
    public function toString(array $custom_props = array()) {
        $args = array_merge(
                Array(
                    'name' => $this->name(),
                    'id' => $this->id(),
                ), $this->render_attributes
        );
        return '<textarea '.static::render_attributes($args+$custom_props).'>'.htmlspecialchars($this->form_data(true)).'</textarea>';
    }
};