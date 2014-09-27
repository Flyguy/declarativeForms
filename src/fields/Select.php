<?php
namespace declarativeForms\fields;


class Select extends Group {
    public function __construct(array $attr = Array()) {
        $multiple = self::pop_arr_item($attr, 'multiple');
        if(isset($multiple)) {
            $this->multiple = $multiple;
        }
        parent::__construct($attr);
    }

    public function toString(array $custom_props=Array()) {
        $args = array_merge(
                Array(
                    'id' => $this->id(),
                    'name' => $this->name().($this->is_multiple() ? '[]':'')
                ), $this->render_attributes
        );
        $select = '<select '.static::render_attributes($args+$custom_props).($this->is_multiple() ?' multiple':'').'>';
        if(!empty($this->choices)) {
            $data = $this->form_data(true);
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