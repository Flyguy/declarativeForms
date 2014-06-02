<?php
namespace declarativeForms\fields;


class Select extends Group {
    public function __construct($default=null, array $validators=Array(), $label=null, $hint=null, $choices=null, $multiple=False, array $extra=Array()) {
        $this->multiple = $multiple;
        parent::__construct($default, $validators, $label, $hint, $choices, $extra);
    }

    public static function create(array $attributes=Array()) {
        return new static(
                self::pop_arr_item($attributes, 'default'),
                self::pop_arr_item($attributes, 'validators', array()),
                self::pop_arr_item($attributes, 'label'),
                self::pop_arr_item($attributes, 'hint'),
                self::pop_arr_item($attributes, 'choices'),
                self::pop_arr_item($attributes, 'multiple'),
                $attributes
        );
    }

    public function toString(array $custom_props=Array()) {
        $args = Array(
            'id' => $this->id(),
            'name' => $this->name().($this->multiple ? '[]':'')
        );
        $select = '<select '.static::render_attributes($args+$custom_props).($this->multiple ?' multiple':'"').'>';
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
                $select.='<option value="'.htmlspecialchars($choice).'"'.($selected ? ' selected="selected"':'').'>'.htmlspecialchars((string)$label).'</option>';
            }
        }
        return $select.'</select>';
    }
}