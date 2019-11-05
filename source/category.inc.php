<?php

class Category
{
    public $name;
    public $entries;
    public $marker;

    public function Inject($string) {
        return str_replace($this->marker, $this->entries, $string);
    }

    public static function Create($name, $marker) {
        $cat = new Category();

        $cat->name = $name;
        $cat->marker = $marker;

        return $cat;
    }
}
