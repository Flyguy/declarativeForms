<?php
namespace declarativeForms;


class ValidationError extends \Exception {
    protected $error;
    public function __construct($error, IField $field, array $args=array(), \Exception $previous = null) {
        $name = $field instanceof fields\Displayed ? $field->label() : $field->name();
        array_unshift($args, $error, $name);
        $this->error = $args;
        parent::__construct(call_user_func_array('sprintf', $args), 0, $previous);
    }
    public function get_error() {
        return $this->error;
    }
};




