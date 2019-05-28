<?php

class Posts {
    public static $list = [];

    function add($title, $source) {
        $post = new Post();
        $post->title = $title;
        $post->source = $source;

        array_push(self::$list, $post);
    }
}
