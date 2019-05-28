<?php

class Post
{
    public static $template = "";
    public const TEMPLATE_FILE = 'post_template.html';

    public $title = '';
    public $source = '';
    public $date = null;
    public $content = '';

    public function Load() {
        $fn = 'posts' . DIRECTORY_SEPARATOR . $this->source;
        $this->content = load_file($fn, false);

        return $this->content != null;
    }

    public function Generate() {
        global $target;

        if($this->content) {
            $fn = $target . 'posts' . DIRECTORY_SEPARATOR . $this->source;

            // skip if we're linking to an existing file
            if(file_exists($fn))
                return true;

            $post = substr(self::$template, 0);

            $post = Common::Inject($post);
            $post = str_replace('__TITLE__', $this->title, $post);
            $post = str_replace('__CONTENT__', $this->content, $post);

            $ok = write_file($fn, $post, false);

            return $ok;
        } else {
            writeln("Post has no content: " . $this->source);
            // if no content, we'll consider it generated
            return true;
        }
    }

    public function getDate() {
        if($this->date)
            return $this->date;
        else
            return '';
    }

    public static function LoadTemplate() {
        self::$template = load_file(self::TEMPLATE_FILE);

        return self::$template != null;
    }
}
