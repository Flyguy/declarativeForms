<?php
namespace declarativeForms;
use declarativeForms\forms;

interface IModel {
    /**
     * @return IField[]
     */
    public function get_fields();
    public function validate();
    public function save_from_form(forms\Base $form);
}