<?php
namespace declarativeForms\fields;
use declarativeForms\IField, declarativeForms\ValidationError;

class GroupWithExtra extends Group implements \IteratorAggregate {
    /**
     * @var IField[]
     */
    protected $extra_fields;
    /**
     * @var Extra[]
     */
    protected $extras;

    public function __construct(array $extra_fields, $default=null, array $validators=Array(), $label=null, $hint=null, $choices=null, $multiple=False) {
        $this->multiple = $multiple;
        $this->extra_fields = $extra_fields;
        parent::__construct($default, $validators, $label, $hint, $choices);
    }

    protected function __clone() {
        parent::__clone();
        $this->extras = Array();
        foreach($this->extra_fields as $key=>$field) {
            $this->extra_fields[$key] = clone $field;
        }
    }

    public static function create(array $attributes=Array()) {
        return new static(
                $attributes['extra_fields'],
                self::get_arr_item($attributes, 'default'),
                self::get_arr_item($attributes, 'validators', array()),
                self::get_arr_item($attributes, 'label'),
                self::get_arr_item($attributes, 'hint'),
                self::get_arr_item($attributes, 'choices'),
                self::get_arr_item($attributes, 'multiple')
        );
    }

    protected function get_lazy_extras() {
        $this->check_bound();
        $extras = Array();
        $data = $this->data(true);
        $extra = new Extra($this->extra_fields);
        if(is_array($data)) {
            foreach($data as $k=>$v) {
                if(!isset($this->choices[$k])) {
                    continue;
                }
                $extras[$k] = $extra->get_clone($this->name, ']', $k, $v);
            }
        }
        return $extras;
    }

    public function validate() {
        $extras = $this->getIterator();
        $result = parent::validate();
        foreach($extras as $field) {
            $result = $result && $field->validate();
        }
        return $result;
    }

    /**
     * @return Extra[]
     */
    public function getIterator() {
        if(!isset($this->extras)) {
            $this->extras = $this->get_lazy_extras();
        }
        return $this->extras;
    }

    public function toString() {
        $html ='<div class="GroupWithExtra">';
        $html.=Select::toString();
        $html.='<div class="elems">';
        foreach($this->getIterator() as $k=>$field) {
            if(!isset($this->choices[$k])) {
                continue;
            }
            $html.= '<div class="elem"><label>'.$this->choices[$k].'</label>'.$field."</div>";
        }
        $html.='</div>';
        return $html.'</div>';
    }
}