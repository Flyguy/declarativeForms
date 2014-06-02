<?php
namespace declarativeForms\fields;
use declarativeForms\IField;
use Traversable;

class Extra extends Base implements \ArrayAccess, \IteratorAggregate {
    /**
     * @var IField[]
     */
    protected $fields;
    /**
     * @param IField[] $extra_fields
     * @param mixed $default
     * @param array $validators
     */
    public function __construct(array $fields, $default=null, array $validators=Array(), array $extra=Array()) {
        $this->fields = $fields;
        parent::__construct($default, $validators, $extra);
    }

    public function validate() {
        $result = parent::validate();
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

    public function __get($field_name) {
        if(isset($this->fields[$field_name])) {
            return $this->fields[$field_name];
        }
        $trace = debug_backtrace();
        throw new \Exception("Undefined field name '".$field_name."' in ".$trace[0]['file']." on line ".$trace[0]['line']."!");
    }

    public function name() {
        $this->check_bound();
        return $this->prefix."[".$this->name."]";
    }

    public function bind($prefix, $postfix, $name, $value) {
        parent::bind($prefix, $postfix, $name, $value);
        foreach($this->fields as $name=>$field) {
            $val = isset($value[$name]) ? $value[$name] : NULL;
            $field->bind($this->name()."[", "]", $name, $val);
        }
    }

    protected function __clone() {
        foreach($this->fields as $name=>$field) {
            $this->fields[$name] = clone $field;
        }
    }

    public function is_hidden() {
        return false;
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
        $html = '<div id="'.$this->id().'">';
        if(count($hidden_elems)) {
            $html.='<div style="display:none;">'.implode("\r\n", $hidden_elems).'</div>';
        }
        $html.=implode("<br/>", $elems);
        $html.='</div>';
        return $html;
    }

    public static function create(array $attributes=Array()) {
        $fields = $attributes['fields'];
        unset($attributes['fields']);
        return new static (
            $fields,
            static::pop_arr_item($attributes, 'default'),
            static::pop_arr_item($attributes, 'validators'),
            $attributes
        );
    }

    public function offsetExists($offset) {
        return isset($this->fields[$offset]);
    }

    public function offsetGet($offset) {
        return $this->fields[$offset];
    }

    public function offsetSet($offset, $value) {
        throw new \Exception("OffsetSet don't allowed here.");
    }

    public function offsetUnset($offset) {
        throw new \Exception("OffsetUnSet don't allowed here.");
    }

    public function getIterator() {
        return $this->fields;
    }
}