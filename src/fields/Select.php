<?php
namespace declarativeForms\fields;


class Select extends Group {
    public function __construct($default=null, array $validators=Array(), $label=null, $hint=null, $choices=null, $multiple=False) {
        $this->multiple = $multiple;
        parent::__construct($default, $validators, $label, $hint, $choices);
    }

    public static function create(array $attributes=Array()) {
        return new static(
                self::get_arr_item($attributes, 'default'),
                self::get_arr_item($attributes, 'validators', array()),
                self::get_arr_item($attributes, 'label'),
                self::get_arr_item($attributes, 'hint'),
                self::get_arr_item($attributes, 'choices'),
                self::get_arr_item($attributes, 'multiple')
        );
    }

    public function toString() {
        $select = '<select id="'.$this->id().'" name="'.$this->name().($this->multiple ? '[]" multiple':'"').'>';
        if(!empty($this->choices)) {
            $data = $this->data(true);
            foreach ($this->choices as $choice => $label) {
                $selected = false;
                if($this->multiple) {
                    if(is_array($data) && in_array($choice, $data)) {
                        $selected = true;
                    }
                } else {
                    if($data == $choice) {
                        $selected = true;
                    }
                }
                $select.='<option value="'.htmlspecialchars($choice).'"'.($selected ? ' selected="selected"':'').'>'.htmlspecialchars($label).'</option>';
            }
        }
        return $select.'</select>';
    }
}