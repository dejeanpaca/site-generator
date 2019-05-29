<?php

class Header
{
    public const HEADER_FILE = 'header.html';
    public const FOOTER_FILE = 'footer.html';
    public const HEADER_MARKER = '__HEADER__';
    public const FOOTER_MARKER = '__FOOTER__';
    public static $header_content = "";
    public static $footer_content = "";

    public static function Load() {
        self::$header_content = load_file_optional(Common::$source . self::HEADER_FILE);
        self::$footer_content = load_file_optional(Common::$source . self::FOOTER_FILE);
    }

    public static function Inject($string) {
        $string = str_replace(self::HEADER_MARKER, self::$header_content, $string);

        return str_replace(self::FOOTER_MARKER, self::$footer_content, $string);
    }
}
