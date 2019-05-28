<?php

class Header
{
    public const HEADER_FILE = 'header.html';
    public const HEADER_MARKER = '__HEADER__';
    public static $content = "";

    public static function Load() {
        self::$content = load_file(self::HEADER_FILE);
    }

    public static function Inject($string) {
        return str_replace(self::HEADER_MARKER, self::$content, $string);
    }
}
