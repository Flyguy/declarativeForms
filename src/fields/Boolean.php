<?php
namespace declarativeForms\fields;

class Boolean extends Text {
    protected $type = 'checkbox';

    protected function process_default($default) {
        return !!$default;
    }

    protected function cleanup_data($data) {
        if(!empty($data)) {
            return true;
        }
        return false;
    }
    public function toString(array $custom_props=Array()) {
        $args =array_merge(
                Array(
                    'type' => $this->type,
                    'name' => $this->name(),
                    'id' => $this->id()
                ), $this->render_attributes
        );
        if(!isset($custom_props['checked']) && $this->form_data(true)) {
            $custom_props['checked'] = 'checked';
        }
        return '<input '.static::render_attributes($args+$custom_props).' value="1"/>';
    }
}