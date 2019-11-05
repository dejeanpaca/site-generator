<?php

$mdPageType = new PageType();
$mdPageType->templateFile = 'page_template.html';
$mdPageType->SetDirectory('md_pages');
$mdPageType->class = '\MarkdownPage';

class MarkdownPage extends Page
{
    function __construct() {
        global $mdPageType;

        parent::__construct();

        $this->type = $mdPageType;
        $this->markers = new Markers();
    }

    public function getContent() {
        if(MD::$converter == null)
            MD::initialize();

        $content = $this->Inject($this->content);

        return MD::$converter->convertToHtml($content);
    }
}
