<?php

class PageType
{
    public $templateFile = 'page_template.html';
    public $source_dir = '';
    public $output_dir = '';
    public $template = "";

    public function LoadTemplate() {
        $this->template = load_file(Common::$source . $this->templateFile);

        return $this->template != null;
    }
}
