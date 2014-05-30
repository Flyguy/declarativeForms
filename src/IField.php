<?php
namespace declarativeForms;

interface IField {
    public function raw_data();
    public function data();
    public function id();
    public function name();
    public function bind($prefix, $postfix, $name, $value);
    public function get_base_name();
    public function add_error($error);
    public function add_errors($errors);
    public function &errors();
    public function validate();
    public function is_hidden();
    public function has_errors();
    public function is_required();
    public function having_validator(array $validator);
    public function get_validator_args(array $validator);
    public function get_clone($prefix, $field_name, $value, $postfix);
    public function toString();
    public static function create(array $attributes=Array());
}