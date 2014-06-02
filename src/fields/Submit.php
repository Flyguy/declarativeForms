<?php
namespace declarativeForms\fields;

class Submit extends Text {
    protected $type = 'submit';
    public function toString(array $custom_props=Array()) {
        $args = Array(
            'type' => $this->type
        );
        return '<input '.static::render_attributes($args+$custom_props).' value="'.$this->label().'"/>';
    }
}