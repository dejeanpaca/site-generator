<?php

class Markers
{
    /** @var string[] */
    public $list = [];

    public function Add($marker, $content) {
        $this->list[$marker] = $content;
    }

    public function Has($marker) {
        return array_key_exists($marker, $this->list);
    }

    public function Get($marker) {
        if(array_key_exists($marker, $this->list))
            return $this->list[$marker];
        else
            return '';
    }

    public function Inject($string) {
        // global markers
        foreach ($this->list as $marker => $content) {
            $string = str_replace($marker, $content, $string);
        }

        return $string;
    }
}
