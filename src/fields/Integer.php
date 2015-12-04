<?php
namespace declarativeForms\fields;
use declarativeForms\validators;

class Integer extends Number {
    public function __construct(array $attr) {
        parent::__construct($attr);
        $this->assign_render_attribute('step', 1);
    }

    protected function assign_standard_validators() {
        return Array(
            validators::is_integer()
        );
    }
}