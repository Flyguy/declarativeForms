<?php
namespace declarativeForms\types;

class DateTime extends \DateTime {
    protected $default_format = 'Y-m-d';

    public function __construct($time='now', $timezone=null, $format=null) {
        if($format) {
            $this->format = $format;
        }
        parent::__construct($time, $timezone);
    }

    public function set_default_format($format) {
        $this->default_format = $format;
    }

    public static function createFromFormat($format, $time, $timezone=null) {
        if(!$timezone) {
            $timezone = new \DateTimeZone('UTC');
        }
        try {
            $datetime = parent::createFromFormat($format, $time, $timezone);
        } catch (\Exception $e) {
            $datetime = null;
        }
        if(!$datetime) {
            return NULL;
        }
        return static::createFromDateTime($datetime, $format);
    }

    public static function createFromDateTime(\DateTime $datetime, $format) {
        return new static('@'.$datetime->format('U'), $datetime->getTimezone(), $format);
    }

    public function __toString() {
        return $this->format($this->default_format);
    }
}