<?php

class Module
{
    /** @var Module[] */
    public static $modules = [];

    public $name = 'unknown';

    public function __construct() {
        Module::$modules[] = $this;
        writeln('Using module ' . $this->name);
    }

    public function Load() {

    }

    public function OnPost($post) {

    }

    public function Inject($string) {
        return $string;
    }

    public function Done() {

    }
}
