<?php

class PageType
{
    public $templateFile = 'page_template.html';
    public $source_dir = 'pages' . DIRECTORY_SEPARATOR;
    public $output_dir = 'pages' . DIRECTORY_SEPARATOR;
    public $template = "";
    /** @var class */
    public $class = null;

    // is this page type a post
    public $post = false;

    /** @var PageType[] */
    public static $types = [];

    function __construct() {
        self::$types[] = $this;
    }

    public function LoadTemplate() {
        $this->template = load_file(Common::$source . Replacer::TEMPLATE_SOURCE . $this->templateFile);

        return $this->template != null;
    }

    /** load all pages of this type */
    public function Load() {
        $source = Common::$source . $this->source_dir;

        $path_info = pathinfo($this->templateFile);
        $required_ext = $path_info['extension'];

        $files = scandir($source);

        if($files) {
            foreach($files as $file) {
                if($file == '.' || $file == '..')
                    continue;

                $path_info = pathinfo($file);
                $ext = $path_info['extension'];

                $fn = $source . $file;

                if($ext != $required_ext)
                    continue;

                // do not add if already added to the list
                if(Pages::find($fn))
                    continue;


                $page = new $this->class;
                $page->source = $file;

                Pages::add($page);
            }
        }
    }
}

class SingletonPageType extends PageType
{
    /** Singleton pages do not load anything */
    public function Load() {

    }
}
