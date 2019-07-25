<?php

$pageType = new PageType();

class Page
{
    public $title = '';
    public $source = '';
    public $date = null;
    public $content = '';

    public $type = null;

    function __construct() {
        global $pageType;

        $this->type = $pageType;
    }

    public function getFn($base, $dir) {
        $fn = $base . $dir . $this->source;

        return $fn;
    }

    public function Load() {
        $fn = $this->getFn(Common::$source, $this->type->source_dir);

        $this->content = load_file($fn, false);

        return $this->content != null;
    }

    public function Inject($string) {
        $string = Common::inject($string);

        $string = str_replace('__DATE__', $this->getDate(), $string);
        return str_replace('__TITLE__', $this->title, $string);
    }

    public function Generate() {
        if($this->content) {
            $fn = $this->getFn(Common::$target, $this->type->output_dir);

            // skip if we're linking to an existing file
            if(file_exists($fn))
                return true;

            $post = substr($this->type->template, 0);

            $post = str_replace('__CONTENT__', $this->getContent(), $post);
            $post = $this->Inject($post);

            // perform additional processing (such as converting markdown to html)
            $post = $this->process($post);

            // check file extension to be html

            $path_info = pathinfo($fn);
            $ext = $path_info['extension'];

            if($ext != 'html') {
                $oldFn = $fn;

                $fn = $path_info['dirname'] + $path_info['basename'] + '.html';
                writeln('Renamed ' . $fn . ' to ' . $fn);
            }

            // done, write file
            $ok = write_file($fn, $post, false);

            return $ok;
        } else {
            writeln("Page has no content: " . $this->source);
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

    // perform content conversion to html or just return $content
    public function getContent() {
        return $this->content;
    }

    // perform additional processing on this post (if any))
    public function process($post) {
        return $post;
    }
}
