<?php
namespace declarativeForms\fields;

class Text extends Displayed {
    protected $type = 'text';

    public function toString(array $custom_props=Array()) {
        $args = array_merge(
                Array(
                    'type' => $this->type,
                    'name' => $this->name(),
                    'id' => $this->id(),
                ), $this->render_attributes
        );
        return '<input '.static::render_attributes($args+$custom_props).' value="'.htmlspecialchars($this->form_data(true)).'"/>';
    }
}