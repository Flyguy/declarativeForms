<?php
namespace declarativeForms\types;

class Date extends DateTime {
    public function __construct($time='now', $timezone=null, $format=null) {
        if($format) {
            $this->format = $format;
        }
        parent::__construct($time, $timezone);
        $this->setTime(0,0,0);
    }
}