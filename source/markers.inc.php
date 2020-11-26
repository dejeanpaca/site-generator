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
        return array_key_exists($marker, $this->list) ? $this->list[$marker] : '';
    }

    public function Inject($string) {
        // global markers
        foreach ($this->list as $marker => $content) {
            $string = str_replace($marker, $content, $string);
        }

        return $string;
    }
}
