<?php
namespace declarativeForms\fields;
use declarativeForms\ValidationError, declarativeForms\IChoices;


abstract class Group extends Displayed {
    protected $choices;
    protected $multiple = false;
    public function __construct (array $attr=Array()) {
        $this->choices = self::pop_arr_item($attr, 'choices');
        parent::__construct($attr);
    }

    public function set_choices($choices) {
        $this->choices = $choices;
    }

    public function get_choices($return_null=false) {
        return isset($this->choices) || $return_null ? $this->choices : Array();
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

    public function form_data($default=false) {
        if($this->multiple) {
            if (!$default || $this->form_data!==null) {
                return $this->form_data;
            } else {
                $def = $this->get_default();
                return $def ? $def : Array();
            }
        } else {
            return parent::form_data($default);
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