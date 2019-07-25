<?php

$mdPageType = new PageType();
$mdPageType->templateFile = 'page_template.html';
$mdPageType->source_dir = 'pages' . DIRECTORY_SEPARATOR;
$mdPageType->output_dir = 'pages' . DIRECTORY_SEPARATOR;

class MarkdownPage extends Page
{
    function __construct() {
        global $mdPageType;

        $this->type = $mdPageType;
    }

    public function getContent() {
        if(MD::$converter == null)
            MD::initialize();

        $content = $this->Inject($this->content);

        return MD::$converter->convertToHtml($content);
    }
}
