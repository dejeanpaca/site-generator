<?php

class Header
{
    public const HEADER_FILE = 'header.html';
    public static $content = "";

    public static function Load() {
        self::$content = load_file(self::HEADER_FILE);
    }
}
