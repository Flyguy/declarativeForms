<?php
namespace declarativeForms\fields;
use declarativeForms\validators;

class Number extends Text {
    protected $type = 'number';
    protected function assign_standard_validators() {
        return Array(
            validators::is_number()
        );
    }
}