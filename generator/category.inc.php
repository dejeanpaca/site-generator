<?php

class Category
{
    public $name;
    public $entries;
    public $marker;

    public function Inject($string) {
        return str_replace($this->marker, $this->entries, $string);
    }
}
