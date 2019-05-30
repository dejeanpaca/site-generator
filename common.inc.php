<?php

class Replacer
{
    public $marker = '';
    public $file = '';
    public $content = '';

    public function Load() {
        if($this->file)
            $this->content = load_file(Common::$source . $this->file);
    }

    public function Inject($string) {
        return str_replace($this->marker, $this->content, $string);
    }
}

class Common
{
    public const POST_LIST_MARKER = '__POST_LIST__';

    public static $source = 'site' . DIRECTORY_SEPARATOR;
    public static $target = 'output' . DIRECTORY_SEPARATOR;
    public static $post_list = '';
    public static $copy_list = [];
    public static $replacers = [];

    public static function Inject($string) {
        $string = CSS::inject($string);

        foreach (self::$replacers as $replacer) {
            $string = $replacer->Inject($string);
        }

        return str_replace(self::POST_LIST_MARKER, self::$post_list, $string);
    }

    public static function Add($marker, $file) {
        $replacer = new Replacer();
        $replacer->marker = $marker;
        $replacer->file = $file;

        array_push(self::$replacers, $replacer);
    }

    public static function Load() {
        foreach(self::$replacers as $replacer) {
            $replacer->Load();
        }
    }
}
