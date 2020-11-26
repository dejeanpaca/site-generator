<?php

class Replacer
{
    public const TEMPLATE_SOURCE = 'templates' . DIRECTORY_SEPARATOR;

    public $marker = '';
    public $file = '';
    public $content = '';
    public $file_path;

    public function Load() {
        $path = $this->file_path ? $this->file_path : Base::$source . self::TEMPLATE_SOURCE . $this->file;
        $this->content = load_file($path);
    }

    public function Inject($string) {
        return str_replace($this->marker, $this->content, $string);
    }
}
