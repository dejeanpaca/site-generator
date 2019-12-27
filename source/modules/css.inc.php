<?php

class CSS extends Module
{
    public const CSS_MARKER = '__CSS__';
    public static $list = [];
    public static $content = "";

    public static $module = null;

    public function __construct() {
        $this->name = 'CSS';

        parent::__construct();
    }

    public function Load() {
        foreach(self::$list as $css) {
            $content = load_file(Base::$source . 'inline_css' . DIRECTORY_SEPARATOR . $css);

            self::$content = self::$content . $content;
        }
    }

    public function Inject($string) {
        if(self::$content) {
            $css = '<style>' . self::$content . '</style>';

            return str_replace(self::CSS_MARKER, $css, $string);
        } else
            return $string;
    }
}

CSS::$module = new CSS();
