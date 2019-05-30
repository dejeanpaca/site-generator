<?php

$pageType = new PageType();
$pageType->templateFile = 'page_template.html';
$pageType->source_dir = '';
$pageType->output_dir = '';

class Page extends Post
{
    function __construct() {
        global $pageType;

        $this->type = $pageType;
    }
}
