<?php

require_once __DIR__ . '/md.inc.php';
require_once __DIR__ . '/md_page.inc.php';
require_once __DIR__ . '/md_post.inc.php';

class MDPages
{
    function add_post($title, $source, $date = null) {
        $page = new MarkdownPost();
        $page->title = $title;
        $page->source = $source;

        if($date)
            $page->date = $date;

        array_push(Pages::$list, $page);
    }

    function add_page($title, $source) {
        $page = new MarkdownPage();
        $page->title = $title;
        $page->source = $source;

        array_push(Pages::$list, $page);
    }
}
