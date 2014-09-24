<?php
namespace declarativeForms\fields;

use declarativeForms\IField, declarativeForms\validators, declarativeForms\ValidationError;
use declarativeForms\ILazy;

abstract class Base implements IField {
    protected $prefix;
    protected $postfix;
    protected $name;
    protected $id;
    protected $raw_data;
    protected $form_data;
    protected $data;
    protected $data_processed;
    protected $extra;
    protected $default;
    protected $raw_default;
    protected $errors = Array();
    protected $validators = Array();
    protected $bound = false;
    protected $render_attributes = array();
    public function __construct($default=null, array $validators=Array(), array $extra=Array()) {
        if(isset($default)) {
            $this->set_default($default);
        }
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

    public function set_default($value) {
        $this->raw_default = $value;
    }

    public function get_default() {
        if(isset($this->raw_default)) {
            $raw_default = $this->raw_default;
            if($raw_default instanceof ILazy) {
                $raw_default = $raw_default($this);
            }
            $this->default = $this->process_default($raw_default);
            unset($this->raw_default);
        }
        return $this->default;
    }

    protected function process_default($value) {
        return $value;
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

    public function data($default=True, $force_process=false) {
        $this->check_bound();
        if(!$this->data_processed || $force_process) {
            $this->data = $this->process_data($this->form_data($default));
            $this->data_parsed = true;
        }
        return $this->data;
    }

    public function raw_data() {
        return $this->raw_data;
    }

    public function form_data($default=false) {
        $this->check_bound();
        return (!$default || $this->form_data!==null ? $this->form_data : $this->get_default());
    }

    public function set_form_data($form_data) {
        $this->check_bound();
        $this->form_data = $form_data;
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
        if($value !== null) {
            $this->form_data = $this->cleanup_data($value);
        }
        $this->data = null;
        $this->data_processed = false;
        $this->bound = true;
    }

    protected function cleanup_data($data) {
        if(!is_string($data)) {
            $data = "";
        }
        return trim($data);
    }

    protected function process_data($data) {
        return $data;
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

    public function assign_render_attribute($attr_name, $attr_value) {
        $this->render_attributes[$attr_name] = $attr_value;
    }

    public function get_render_attribute($attr_name) {
        return isset($this->render_attributes[$attr_name]) ? $this->render_attributes[$attr_name]: NULL;
    }

    public function assign_validator(array $validator) {
        $this->validators[$validator[0]] = $validator[1];
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

    public function errors() {
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

    protected static function render_attributes($attributes) {
        $arr = Array();
        foreach ($attributes as $attr => $value) {
            if(!empty($value)) {
                $arr[]=$attr.'="'.htmlspecialchars($value).'"';
            }
        }
        return join(' ', $arr);
    }

    public static function create(array $attributes = array()) {
        return new static(
                self::pop_arr_item($attributes, 'default'),
                self::pop_arr_item($attributes, 'validators', Array()),
                $attributes
        );
    }
}