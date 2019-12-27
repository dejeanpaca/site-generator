<?php

class Replacer
{
    public const TEMPLATE_SOURCE = 'templates' . DIRECTORY_SEPARATOR;

    public $marker = '';
    public $file = '';
    public $content = '';
    public $file_path;

    public function Load() {
        if($this->file_path)
            $this->content = load_file($this->file_path);
        else
            $this->content = load_file(Base::$source . self::TEMPLATE_SOURCE . $this->file);
    }

    public function Inject($string) {
        return str_replace($this->marker, $this->content, $string);
    }
}
