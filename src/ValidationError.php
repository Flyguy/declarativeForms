<?php
namespace declarativeForms;


class ValidationError extends \Exception {
    protected $error;
    public function __construct($error, array $args=array(), \Exception $previous = null) {
        array_unshift($args, $error);
        $this->error = $args;
        parent::__construct(call_user_func_array('sprintf', $args), 0, $previous);
    }
    public function get_error() {
        return $this->error;
    }
};




