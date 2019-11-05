<?php

$indexType = new IndexPageType();
$indexType->templateFile = 'index_template.html';
$indexType->SetDirectory('');
$indexType->class = '\IndexPage';
$indexType->zIndex = 10000;

class IndexPageType extends PageType
{
    // we'll only load the index page
    public function Load() {
        $index_page = new IndexPage();
        $index_page->source = 'index.html';

        Pages::add($index_page);
    }
}

class IndexPage extends Page
{
    function __construct() {
        global $indexType;

        parent::__construct();

        $this->setType($indexType);
    }
}
