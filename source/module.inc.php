<?php

class Module
{
    /** @var Module[] */
    public static $modules = [];

    public $name = 'unknown';

    public function __construct() {
        array_push(Module::$modules, $this);

        writeln('Using module ' . $this->name);
    }

    public function Load() {

    }

    /** Called when a post is generated
     * @param Page post
     */
    public function OnPost($post) {

    }

    /** called after all posts are generated
     * @param Page post
     */
    public function OnPostDone($post) {

    }

    public function Inject($string) {
        return $string;
    }

    public function Done() {

    }
}
