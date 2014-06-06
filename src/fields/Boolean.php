<?php
namespace declarativeForms\fields;

class Boolean extends Text {
    protected $type = 'checkbox';
    protected $default = "1";

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
        if(!isset($custom_props['checked']) && $this->form_data(true)) {
            $custom_props['checked'] = 'checked';
        }
        return parent::toString($custom_props);
    }
}