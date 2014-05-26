<?php
namespace declarativeForms\fields;
use declarativeForms\validators;

class Email extends Text {
    protected $type = 'email';
    protected function assign_standard_validators() {
        return Array(
            validators::is_email()
        );
    }
}