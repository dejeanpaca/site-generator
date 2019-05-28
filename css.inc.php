<?php

class CSS
{
    public const CSS_MARKER = '__CSS__';
    public static $list = [];
    public static $content = "";

    public static function Load() {
        foreach(self::$list as $css) {
            $content = load_file('inline_css' . DIRECTORY_SEPARATOR . $css);

            self::$content = self::$content . $content;
        }
    }

    public static function Inject($string) {
        if(self::$content) {
            $css = '<style>' . self::$content . '</style>';

            return str_replace(self::CSS_MARKER, $css, $string);
        } else
            return $string;
    }
}
