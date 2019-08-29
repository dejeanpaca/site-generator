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

    /** Called when a post is generated
     * @param Page post
     */
    public function OnPost($post) {

    }

    public function Inject($string) {
        return $string;
    }

    public function Done() {

    }
}
