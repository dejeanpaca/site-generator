<?php

class Posts {
    public static $list = [];

    function add($title, $source, $date = null) {
        $post = new Post();
        $post->title = $title;
        $post->source = $source;

        if($date)
            $post->date = $date;

        array_push(self::$list, $post);
    }
}
