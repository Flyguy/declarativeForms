<?php
namespace declarativeForms;
use declarativeForms\fields;
/**
 * Class validators
 * @package declarativeForms
 * @method static array length($min_length = 0, $max_length = 0) Validating length of a string or an array
 * @method static array is_date($date_format='Y-m-d') Validate date string
 * @method static array is_datetime($datetime_format='Y-m-d H:i:s') Validate datetime string
 * @method static array is_email() Validate email string
 * @method static array is_url Validate url string
 * @method static array is_number Validate string as float
 * @method static array is_integer Validate string as integer
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


    public static final function _call_required(IField $field) {
        $data = $field->form_data();
        if(empty($data)) {
            throw new ValidationError('Field "%1$s" is empty!', $field);
        }
    }

    public static function _call_is_date(IField $field, $format='Y-m-d') {
        static::_call_is_datetime($field, $format);
    }

    public static function _call_is_datetime(IField $field, $format='Y-m-d H:i:s') {
        $data = $field->form_data();
        if(!empty($data)) {
            $d = \DateTime::createFromFormat($format, $data);
            if(!$d || $d->format($format) != $data) {
                throw new ValidationError('Field "%1$s" is empty!', $field);
            }
        }
    }

    public static function _call_length(IField $field, $min_length = 0, $max_length = 0) {
        $data = $field->form_data();
        if(!empty($data)) {
            if(is_array($data)) {
                $length = count($data);
            } else {
                $length = strlen($data);
            }
            if ($max_length && $max_length < $length) {
                throw new ValidationError('Field length "%1$s" must be less than %2$i', $field, $length);
            }
            if ($min_length && $min_length > $length) {
                throw new ValidationError('Field length "%1$s" must be greater than %2$i', $field, $length);
            }
        }
    }

    public static function _call_is_email(IField $field) {
        $data = $field->form_data();
        if(!empty($data)) {
            if(!filter_var($data, \FILTER_VALIDATE_EMAIL)) {
                throw new ValidationError('Invalid data in "%1$s"', $field);
            }
        }
    }

    public static function _call_is_url(IField $field) {
        $data = $field->form_data();
        if(!empty($data)) {
            if(!filter_var($data, \FILTER_VALIDATE_URL)) {
                throw new ValidationError('Invalid data in "%1$s"', $field);
            }
        }
    }

    public static function _call_is_number(IField $field) {
        $data = $field->form_data();
        if(!empty($data)) {
            if(!filter_var($data, \FILTER_VALIDATE_FLOAT)) {
                throw new ValidationError('Invalid data in "%1$s"', $field);
            }
        }
    }

    public static function _call_is_integer(IField $field) {
        $data = $field->form_data();
        if(!empty($data)) {
            if(!filter_var($data, \FILTER_VALIDATE_INT)) {
                throw new ValidationError('Invalid data in "%1$s"', $field);
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