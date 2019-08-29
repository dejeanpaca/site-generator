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
    }
}

class SingletonPageType extends PageType
{
    /** Singleton pages do not load anything */
    public function Load() {

    }
}
