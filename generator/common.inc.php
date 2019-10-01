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
            $this->content = load_file(Common::$source . self::TEMPLATE_SOURCE . $this->file);
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
    /** @var Markers */
    public static $markers = null;

    public static function Initialize() {
        self::$markers = new Markers();
    }

    public static function Inject($string) {
        // module injection
        foreach(Module::$modules as $module) {
            $string = $module->Inject($string);
        }

        // global markers
        $string = self::$markers->Inject($string);

        // replacers
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

Common::Initialize();
