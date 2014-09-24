<?php
namespace declarativeForms;

interface ILazy {
    public function __invoke();
}