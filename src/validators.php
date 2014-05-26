<?php
namespace declarativeForms;

/**
 * Class validators
 * @package declarativeForms\base
 * @method static array length($min_length = 0, $max_length = 0) Validating length of a string or an array
 * @method static array is_email() Validate email string
 */
class validators {
    const magic_word = '_call_';
    private final function __construct() {}

    /**
     * Mark as required field
     * @return array
     */
    public static final function required() {
        return array(__CLASS__.'::'.self::magic_word.'required', Array());
    }

    public static function _call_required(IField $field) {
        $data = $field->data();
        if(empty($data)) {
            throw new ValidationError('Field "%1$s" is empty!', Array($field->label()));
        }
    }

    public static function _call_length(IField $field, $min_length = 0, $max_length = 0) {
        $data = $field->data();
        if(empty($data)) {
            if($min_length > 0) {
                static::_call_required($field);
            }
            $length = 0;
        } else {
            if(is_array($data)) {
                $length = count($data);
            } else {
                $length = strlen($data);
            }
        }
        if ($max_length && $max_length < $length) {
            throw new ValidationError("Error");
        }
        if ($min_length && $min_length > $length) {
            throw new ValidationError("Error2");
        }
    }

    public static function _call_is_email(IField $field) {
        $data = $field->data();
        if(!empty($data)) {
            if(!filter_var($data, \FILTER_VALIDATE_EMAIL)) {
                throw new ValidationError("Error3");
            }
        }
    }

    public static function __callStatic($func, $arguments) {
        $class = get_called_class();
        $orig_func = static::magic_word.$func;
        if(!method_exists($class, $orig_func)) {
            throw new \Exception('Validator with the name '.$class.'::'.$func.' is undefined!');
        }
        return Array($class.'::'.$orig_func, $arguments);
    }
}