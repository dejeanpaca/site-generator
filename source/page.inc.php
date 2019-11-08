<?php

$pageType = new PageType();
$pageType->zIndex = 1000;

class Page
{
    public $title = '';
    public $summary = '';

    /** what category this page goes into */
    public $category = 'posts';

    /** page time as a timestamp */
    public $date = 0;
    /** page time as string */
    public $date_string = null;

    public $source = '';
    public $content = '';
    /** generated content of the page */
    public $generated = '';

    /** zIndex for this page, to sort during generation */
    public $zIndex = 0;

    /** @var PageType */
    public $type = null;

    public static $postDateFormat = 'Y-m-d';

    /** per page markers
     * @var Markers
    */
    public $markers = null;

    function __construct() {
        global $pageType;

        $this->markers = new Markers();
        $this->setType($pageType);
    }

    public function setType($type) {
        $this->type = $type;
        $this->zIndex = $type->zIndex;
    }

    public function getFn($base, $dir) {
        $fn = $base . $dir . $this->source;

        return $fn;
    }

    public function getTargetFn() {
        return $this->getFn(Base::$target, $this->type->output_dir);
    }

    public function getLink() {
        $link = '/' . $this->type->output_dir . $this->source;

        $link = str_replace('\\', '/', $link);

        return $link;
    }

    public function Load() {
        $fn = $this->getFn(Base::$source, $this->type->source_dir);

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

                        if($key == '@title')
                            $this->title = $value;
                        else if($key == '@summary')
                            $this->summary = $value;
                        else if($key == '@date') {
                            $this->date_string = $value;

                            $this->date = strtotime($this->date_string);
                        } else if($key == '@marker') {
                            $marker_kv = explode(' ', $kv[1], 2);

                            $mkey = $marker_kv[0];
                            $mvalue = '';

                            if(count($marker_kv) > 1)
                                $mvalue = $marker_kv[1];

                            $this->markers->Add($mkey, $mvalue);
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
        // page markers
        $string = $this->markers->Inject($string);

        $string = Common::Inject($string);

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
            $page = substr($this->type->template, 0);

            $content = $this->getContent();

            $page = str_replace('__CONTENT__', $content, $page);
            $page = $this->Inject($page);

            // perform additional processing (such as converting markdown to html)
            $page = $this->process($page);

            $this->generated = $page;
        } else {
            writeln("Page has no content: " . $this->source);
            // if no content, we'll consider it generated
            return true;
        }
    }

    public function Write($force = false) {
        if($this->generated) {
            $fn = $this->getTargetFn();

            // skip if we're linking to an existing file, or overwrite if forced
            if(file_exists($fn) && !$force)
                return true;

            $fn = $this->correctExtension($fn);

            // done, write file
            $ok = write_file($fn, $this->generated, false);

            return $ok;
        } else {
            writeln("Page has no generated content, skipping write: " . $this->source);
            return true;
        }
    }

    public function getDate($format = null) {
        if(!$format)
            $format = self::$postDateFormat;

        if($this->date)
            return date($format, $this->date);
        else
            return $this->date_string;
    }

    // perform content conversion to html or just return $content
    public function getContent() {
        return $this->content;
    }

    // perform additional processing on this page (if any))
    public function process($page) {
        return $page;
    }
}
