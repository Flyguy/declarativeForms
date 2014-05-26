<?php
namespace declarativeForms\forms;
use declarativeForms\IField;

abstract class Base {
    protected $input_arr;
    protected $model;
    protected $prefix;
    protected $postfix;
    /**
     * @var IField[]
     */
    protected $fields = array();
    protected $errors = array();

    abstract protected function get_fields();

    public function __construct($input_arr, $prefix="", $postfix="") {
        $this->input_arr = $input_arr;
        $this->prefix = $prefix;
        $this->postfix = $postfix;
        $this->bind();
    }

    protected function bind() {
        $fields = $this->fields();
        foreach($fields as $field_name => $field) {
            $value = null;
            $prefix_field_name = $this->prefix.$field_name;
            if(isset($this->input_arr[$prefix_field_name])) {
                $value = $this->input_arr[$prefix_field_name];
            }
            $field->bind($this->prefix, $this->postfix, $field_name, $value);
        }
    }

    public function errors() {
        return $this->errors;
    }

    public function add_errors($errors) {
        foreach($errors as $error) {
            $this->add_error($error);
        }
    }

    public function add_error($error) {
        $this->errors[] = $error;
    }

    public function has_errors() {
        return count($this->errors) > 0;
    }


    /**
     * @param $field_name
     * @return IField
     * @throws \Exception
     */
    public function __get($field_name) {
        if(isset($this->fields[$field_name])) {
            return $this->fields[$field_name];
        }
        $trace = debug_backtrace();
        throw new \Exception("Undefined field name '".$field_name."' in ".$trace[0]['file']." on line ".$trace[0]['line']."!");
    }

    /**
     * @return IField[]
     */
    public function fields() {
        if (!count($this->fields)) {
            $this->fields = $this->get_fields();
        }
        return $this->fields;
    }

    public function is_submitted() {
        return $_SERVER['REQUEST_METHOD'] == 'POST';
    }

    public function validate() {
        $result = true;
        foreach($this->fields as $field) {
            if(!$field->validate()) {
                if($field->is_hidden()) {
                    $this->add_errors($field->errors());
                }
                $result = false;
            }
        }
        return $result;
    }

    public function validate_on_submit() {
        return $this->is_submitted() && $this->validate();
    }

    public function toString() {
        $hidden_elems = Array();
        $elems = Array();
        foreach($this->fields as $field) {
            if(!$field->is_hidden()) {
                $elems[]='<label for="'.$field->id().'">'.$field->label().($field->is_required()?'*':"").': </label>'.$field;
            } else {
                $hidden_elems[]=$field->toString();
            }
        }
        $html = '<form method="POST">';
        if(count($hidden_elems)) {
            $html.='<div style="display:none;">'.implode("\r\n", $hidden_elems).'</div>';
        }
        $html.=implode("<br/>", $elems);
        $html.='</form>';
        return $html;
    }

    public function __toString() {
        try {
            return $this->toString();
        } catch(\Exception $e) {
            trigger_error($e, E_USER_ERROR);
        }
        return "";
    }
}
