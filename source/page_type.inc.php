<?php

class PageType
{
    public $templateFile = 'page_template.html';
    public $source_dir = 'pages' . DIRECTORY_SEPARATOR;
    public $output_dir = 'pages' . DIRECTORY_SEPARATOR;
    public $template = "";
    /** @var class */
    public $class = '\Page';

    /** default zIndex for pages of this type */
    public $zIndex = 0;

    // is this page type a post
    public $category = '';

    /** @var PageType[] */
    public static $types = [];

    function __construct() {
        array_push(self::$types, $this);
    }

    public function SetDirectory($directory) {
        if($directory) {
            $this->source_dir = $directory . DIRECTORY_SEPARATOR;
            $this->output_dir = $directory . DIRECTORY_SEPARATOR;
        } else {
            $this->source_dir = '';
            $this->output_dir = '';
        }
    }

    public function LoadTemplate() {
        $this->template = load_file(Base::$source . Replacer::TEMPLATE_SOURCE . $this->templateFile);

        return $this->template != null;
    }

    /** load all pages of this type */
    public function Load() {
        $this->LoadPath('');
    }

    /** load all pages from a given path */
    public function LoadFromPath($fullpath, $path) {
        $path_info = pathinfo($this->templateFile);
        $required_ext = $path_info['extension'];

        $files = scandir($fullpath);

        if($files) {
            foreach($files as $file) {
                if($file == '.' || $file == '..')
                    continue;

                $path_info = pathinfo($file);

                // no extension, ignore this file
                if(!array_key_exists('extension', $path_info))
                    continue;

                // skip if it is a directory
                if(is_dir($file))
                    continue;

                $ext = $path_info['extension'];

                $fn = $path . $file;

                if($ext != $required_ext)
                    continue;

                // do not add if already added to the list
                if(Pages::find($fn))
                    continue;

                $page = $this->InstancePage();
                $page->source = $path . $file;
                $page->fullPath = $fullpath . $file;

                Pages::add($page);
            }
        }
    }

    // load path recursively
    public function LoadPath($path) {
        $source = Base::$source . $this->source_dir;
        $current = $source . $path;

        $dir = opendir($current);
        $this->LoadFromPath($current, $path);

        if($dir === false)
            return;

        while ($file = readdir($dir)) {
            if (($file != '.') && ($file != '..')) {
                $newPath = $path . $file;

                if (is_dir($source . $newPath))
                    $this->LoadPath($newPath . '/');
            }
        }

        closedir($dir);
    }

    public function InstancePage() {
        $page = new $this->class;
        $page->setType($this);
        $page->zIndex = $this->zIndex;

        return $page;
    }
}
