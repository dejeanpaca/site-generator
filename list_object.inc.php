<?php

class Post
{
    public static $list = [];
    public static $template = "";

    public $title = '';
    public $source = '';
    public $content = '';

    public function Load() {
        $fn = 'posts' . DIRECTORY_SEPARATOR . $this->source;
        $this->content = @file_get_contents($fn);

        if($this->content == null)
            writeln('Failed to load: ' . $fn);

        return $this->content != null;
    }

    public function Generate() {
        global $target;

        if($this->content) {
            $post = substr(self::$template, 0);
            $fn = $target . 'posts' . DIRECTORY_SEPARATOR . $this->source;

            $post = str_replace('__TITLE__', $this->title, $post);
            $post = str_replace('__CONTENT__', $this->content, $post);


            $ok = @file_put_contents($fn, $post);

            if(!$ok)
                writeln('Failed to create file: ' . $fn);

            return $ok;
        }

        return false;
    }

    public static function LoadTemplate() {
        self::$template = @file_get_contents('post_template.html');

        return self::$template != null;
    }
}

function add_post($title, $source) {
    $post = new Post();
    $post->title = $title;
    $post->source = $source;

    array_push(Post::$list, $post);
}
