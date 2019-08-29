<?php

require_once __DIR__ . '/page.inc.php';
require_once __DIR__ . '/post.inc.php';
require_once __DIR__ . '/index.inc.php';

class Pages
{
    public static $list = [];

    public static function add_post($title, $source, $date = null) {
        $page = new Post();
        $page->title = $title;
        $page->source = $source;

        if($date)
            $page->date = $date;

        array_push(self::$list, $page);
    }

    public static function add_page($title, $source) {
        $page = new Page();
        $page->title = $title;
        $page->source = $source;

        array_push(self::$list, $page);
    }

    public static function add_index($title, $source) {
        $page = new IndexPage();
        $page->title = $title;
        $page->source = $source;

        array_push(self::$list, $page);
    }
}
