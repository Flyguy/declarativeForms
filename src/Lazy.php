<?php
namespace declarativeForms;

class Lazy implements ILazy {
    protected $callback;
    public function __construct($callback) {
        $this->callback = $callback;
    }

    public function __invoke() {
        $args = func_get_args();
        return call_user_func_array($this->callback, $args);
    }
}