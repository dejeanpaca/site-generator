<?php

class Common
{
    public static $source = 'site' . DIRECTORY_SEPARATOR;
    public static $target = 'output' . DIRECTORY_SEPARATOR;
    public static $copy_list = [];

    public static function Inject($string) {
        $string = CSS::inject($string);
        $string = Header::inject($string);

        return $string;
    }
}
