<?php

class PageType
{
    public $templateFile = 'post_template.html';
    public $source_dir = 'posts/';
    public $output_dir = 'posts/';
    public $template = "";

    public function LoadTemplate() {
        $this->template = load_file(Common::$source . $this->templateFile);

        return $this->template != null;
    }
}
