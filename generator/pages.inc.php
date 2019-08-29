<?php

require_once __DIR__ . '/page.inc.php';
require_once __DIR__ . '/post.inc.php';
require_once __DIR__ . '/index.inc.php';

class Pages
{
    /** @var Page[] */
    public static $list = [];

    public static function add_post($source) {
        $page = new Post();
        $page->source = $source;

        self::add($page);
    }

    public static function add_page($source) {
        $page = new Page();
        $page->source = $source;

        self::add($page);
    }

    public static function add_index($source) {
        $page = new IndexPage();
        $page->source = $source;

        self::add($page);
    }

    public static function add($page) {
        array_push(self::$list, $page);

        writeln('Added: ' . $page->source . ' ' . $page->type->class);
    }

    // find a page by the given name (from sources)
    public static function Find($fn) {
        foreach(self::$list as $page) {
            $page_fn = Common::$source . $page->type->source_dir . $page->source;

            if($page_fn == $fn)
                return $page;
        }

        return null;
    }

    public static function Load() {
        foreach(PageType::$types as $type) {
            $type->Load();
        }
    }
}
