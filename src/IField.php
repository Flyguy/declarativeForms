<?php
namespace declarativeForms;

interface IField {
    public function raw_data();
    public function form_data();
    public function data();
    public function id();
    public function name();
    public function set_default($value);
    public function set_form_data($data);
    public function get_default();
    public function bind($prefix, $postfix, $name, $value);
    public function get_base_name();
    public function extra($extra);
    public function add_error($error);
    public function add_errors($errors);
    public function errors();
    public function validate();
    public function is_hidden();
    public function has_errors();
    public function is_required();
    public function assign_render_attribute($attr_name, $attr_value);
    public function get_render_attribute($attr_name);
    public function assign_validator(array $validator);
    public function having_validator(array $validator);
    public function get_validator_args(array $validator);
    public function get_clone($prefix, $field_name, $value, $postfix);
    public function toString(array $custom_props=Array());
    public static function create();
}