<?php

use League\CommonMark\CommonMarkConverter;

class MD
{
    public static $converter = null;

    public static function initialize() {
        self::$converter = new CommonMarkConverter([
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);
    }
}
