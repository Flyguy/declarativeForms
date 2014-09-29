<?php
namespace declarativeForms\fields;

class Submit extends Text {
    protected $type = 'submit';
    public function toString(array $custom_props=Array()) {
        $args = array_merge(
                Array(
                    'type' => $this->type,
                    'name' => $this->name()
                ), $this->render_attributes
        );
        if(!isset($args['value']) && isset($this->label)) {
            $args['value'] = $this->label;
        }
        return '<input '.static::render_attributes($args+$custom_props).'/>';
    }
}