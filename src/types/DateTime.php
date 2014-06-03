<?php
namespace declarativeForms\types;

class DateTime extends \DateTime {
    protected $format = 'Y-m-d';

    public function __construct($time='now', $timezone=null, $format=null) {
        if($format) {
            $this->format = $format;
        }
        parent::__construct($time, $timezone);
    }

    public static function createFromFormat($format, $time, $timezone=null) {
        if($timezone) {
            $datetime = parent::createFromFormat($format, $time, $timezone);
        } else {
            $datetime = parent::createFromFormat($format, $time);
        }
        if(!$datetime) {
            return NULL;
        }
        //Workaround here
        return new static('@'.$datetime->format('U'), $datetime->getTimezone(), $format);
    }

    public function __toString() {
        return $this->format($this->format);
    }
}