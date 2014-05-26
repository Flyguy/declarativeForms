<?php
namespace declarativeForms;


interface IChoices extends \ArrayAccess, \Iterator, \Countable {
    public function __construct($object, $value_key, $text_key);
    public function reload();
}