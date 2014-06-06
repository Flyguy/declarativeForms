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
}