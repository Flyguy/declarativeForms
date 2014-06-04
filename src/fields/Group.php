<?php
namespace declarativeForms\fields;
use declarativeForms\ValidationError, declarativeForms\IChoices;


abstract class Group extends Displayed {
    protected $choices;
    protected $multiple = false;
    public function __construct ($default=null, array $validators=Array(), $label=null, $hint=null, $choices=null, array $extra=Array()) {
        $this->choices = $choices;
        if(!$default && $this->is_multiple()) {
            $default = Array();
        }
        parent::__construct($default, $validators, $label,  $hint, $extra);
    }

    public function set_choices($choices) {
        $this->choices = $choices;
    }

    public function get_choices() {
        return $this->choices;
    }

    public function is_multiple() {
        return $this->multiple;
    }

    protected function validate_choice($choice) {
        if((!is_array($this->choices) && !$this->choices instanceof IChoices) || empty($this->choices[$choice])) {
            throw new ValidationError('Not a valid choice in the field %1$s', Array($this->label()));
        }
    }

    protected static function validate_choices(Group $field) {
        $data = $field->form_data(true);
        if(empty($data)) {
            return;
        }
        if(is_array($data)) {
            foreach($data as $choice) {
                $field->validate_choice($choice);
            }
        } else {
            $field->validate_choice($data);
        }
    }

    public function assign_standard_validators() {
        return array(
            Array(get_class($this).'::validate_choices', array())
        );
    }

    public static function create(array $attributes=array()) {
        return new static(
                self::pop_arr_item($attributes, 'default'),
                self::pop_arr_item($attributes, 'validators', Array()),
                self::pop_arr_item($attributes, 'label'),
                self::pop_arr_item($attributes, 'hint'),
                self::pop_arr_item($attributes, 'choices'),
                $attributes
        );
    }

    public function form_data($default=false) {
        if($this->multiple) {
            return (!$default || count($this->form_data)) ? $this->form_data : $this->default;
        } else {
            return parent::data();
        }
    }

    protected function cleanup_data($data) {
        if ($this->multiple) {
            if (!is_array($data)) {
                $data = Array();
            }
        } else {
            $data = parent::cleanup_data($data);
        }
        return $data;
    }
}