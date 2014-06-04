<?php
namespace declarativeForms\fields;


class Hidden extends Base {
    public function is_hidden() {
        return true;
    }

    public function toString(array $custom_props=Array()) {
        $args = array(
            'name' => $this->name(),
            'id' => $this->id(),
        );
        return '<input '.static::render_attributes($args+$custom_props).' type="hidden" value="'.htmlspecialchars($this->form_data(true)).'"/>';
    }
}