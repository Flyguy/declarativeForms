<?php
namespace declarativeForms\fields;

use declarativeForms\IField, declarativeForms\validators, declarativeForms\ValidationError;

abstract class Base implements IField {
    protected $prefix;
    protected $postfix;
    protected $name;
    protected $id;
    protected $raw_data;
    protected $data;
    protected $extra;
    protected $default;
    protected $errors = Array();
    protected $validators = Array();
    protected $bound = false;
    public function __construct($default=null, array $validators=Array(), array $extra=Array()) {
        $this->default = $default;
        foreach($validators as $validator) {
            $this->validators[$validator[0]] = $validator[1];
        }
        $this->extra = $extra;
        $standard_validators = $this->assign_standard_validators();
        foreach($standard_validators as $validator) {
            $type = $validator[0];
            if(!isset($this->validators[$type])) {
                $this->validators[$type] = $validator[1];
            }
        }
    }

    public function extra($attr) {
        return array_key_exists($attr, $this->extra) ? $this->extra[$attr] : NULL;
    }

    protected function __clone() {}

    public function get_clone($prefix, $postfix, $field_name, $value) {
        $clone = clone $this;
        $clone->bind($prefix, $postfix, $field_name, $value);
        return $clone;
    }

    public function check_bound() {
        if(!$this->bound) throw new \Exception("Can't get attributes from the unbound field!");
    }

    public function get_base_name() {
        $name = get_called_class();
        if(($pos=strrpos($name, '\\'))!==FALSE) {
            return substr($name, $pos+1);
        }
        return $name;
    }

    public function data($default=false) {
        $this->check_bound();
        return (!$default || strlen($this->data)>0 ? $this->data : $this->default);
    }

    public function raw_data() {
        return $this->raw_data;
    }

    protected function assign_standard_validators() {
        return Array();
    }

    public function name() {
        $this->check_bound();
        return $this->prefix.$this->name.$this->postfix;
    }

    public function id() {
        $this->check_bound();
        if(!isset($this->id)) {
            $this->id = str_replace(Array('[',']'), Array('_',''), $this->name());
        }
        return $this->id;
    }

    public function bind($prefix, $postfix, $field_name, $value) {
        $this->prefix = $prefix;
        $this->postfix = $postfix;
        $this->name = $field_name;
        $this->raw_data = $value;
        $this->data = $this->cleanup_data($value);
        $this->bound = true;
    }

    protected function cleanup_data($data) {
        if(!is_string($data)) {
            $data = "";
        }
        return trim($data);
    }

    protected static function pop_arr_item(&$arr, $item_name, $default = null) {
        if (array_key_exists($item_name, $arr)) {
            $val = $arr[$item_name];
            unset($arr[$item_name]);
            return $val;
        } else {
            return $default;
        }
    }

    public function having_validator(array $validator) {
        return isset($this->validators[$validator[0]]);
    }

    public function get_validator_args(array $validator) {
        if($this->having_validator($validator)) {
            return $this->validators[$validator[0]];
        }
        return NULL;
    }

    public function is_required() {
        return $this->having_validator(validators::required());
    }

    public function add_error($error) {
        $this->errors[] = $error;
    }

    public function add_errors($errors) {
        foreach ($errors as $error) {
            $this->add_error($error);
        }
    }

    public function &errors() {
        return $this->errors;
    }

    public function has_errors() {
        return count($this->errors) != 0;
    }

    public function validate() {
        $errors = array();
        foreach ($this->validators as $type=>$args) {
            try {
                array_unshift($args, $this);
                call_user_func_array($type, $args);
            } catch(ValidationError $e) {
                $errors[] = $e->get_error();
            }
        }
        if(!empty($errors)) {
            $this->add_errors($errors);
            return false;
        }
        return true;
    }

    public function __toString() {
        try {
            return $this->toString();
        } catch(\Exception $e) {
            trigger_error($e, E_USER_ERROR);
        }
        return "";
    }

    public static function create(array $attributes = array()) {
        return new static(
                self::pop_arr_item($attributes, 'default'),
                self::pop_arr_item($attributes, 'validators', Array()),
                $attributes
        );
    }
}