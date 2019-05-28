<?php

class Common
{
    public static function Inject($string) {
        $string = CSS::inject($string);
        $string = Header::inject($string);

        return $string;
    }
}
