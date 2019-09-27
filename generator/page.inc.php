<?php

$pageType = new PageType();
$pageType->class = '\Page';

class Page
{
    public $title = '';
    public $summary = '';
    public $date = null;

    public $source = '';
    public $content = '';

    /** @var PageType */
    public $type = null;

    /** per page markers */
    public $markers = [];

    function __construct() {
        global $pageType;

        $this->type = $pageType;
    }

    public static function AddMarker($marker, $content) {
        self::$markers[$marker] = $content;
    }

    public function getFn($base, $dir) {
        $fn = $base . $dir . $this->source;

        return $fn;
    }

    public function Load() {
        $fn = $this->getFn(Common::$source, $this->type->source_dir);

        $this->content = load_file($fn, false);
        $this->getDescriptor();

        return $this->content != null;
    }

    public function getDescriptor() {
        $start = strlen('<!--');

        // we have a comment
        if(substr($this->content, 0, $start) == '<!--') {
            $pos = strpos($this->content, '-->');

            if($pos !== FALSE) {
                $descriptor = substr($this->content, $start, $pos - $start);

                $lines = explode("\n", $descriptor);
                foreach($lines as $line) {
                    $line = trim($line);
                    $kv = explode(': ', $line, 2);

                    if(count($kv) == 2) {
                        $key = strtolower(trim($kv[0]));
                        $value = trim($kv[1]);

                        if($key == '@title') {
                            $this->title = $value;
                        } else if($key == '@summary') {
                            $this->summary = $value;
                        } else if($key == '@date') {
                            $this->date = $value;
                        } else if(@key == '@marker') {
                            $marker_kv = explode(' ', $kv[1], 2);

                            $mkey = $marker_kv[0];
                            $mvalue = '';

                            if(count(marker_kv) > 1)
                                $value = $marker_kv[1];

                            $this->AddMarker($mkey, $mvalue);
                        }
                    }
                }

                $this->content = substr($this->content, $pos + 4, strlen($this->content) - $pos - 4);

                return;
            }
        }

        writeln('No or invalid descriptor in: ' . $this->type->source_dir . $this->source);
    }

    public function Inject($string) {
        $string = Common::Inject($string);

        // page markers
        foreach ($this->markers as $marker => $content) {
            $string = str_replace($marker, $content, $string);
        }

        $string = str_replace('__DATE__', $this->getDate(), $string);
        return str_replace('__TITLE__', $this->title, $string);
    }

    public function correctExtension($fn) {
        // check file extension to be html

        $path_info = pathinfo($fn);
        $ext = $path_info['extension'];

        if($ext != 'html') {
            $oldFn = $fn;

            $fn = $path_info['dirname'] . DIRECTORY_SEPARATOR . $path_info['filename'] . '.html';
        }

        return $fn;
    }

    public function Generate() {
        if($this->content) {
            $fn = $this->getFn(Common::$target, $this->type->output_dir);

            // skip if we're linking to an existing file
            if(file_exists($fn))
                return true;

            $post = substr($this->type->template, 0);

            $content = $this->getContent();

            $post = str_replace('__CONTENT__', $content, $post);
            $post = $this->Inject($post);

            // perform additional processing (such as converting markdown to html)
            $post = $this->process($post);

            $fn = $this->correctExtension($fn);

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
