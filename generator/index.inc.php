<?php

$indexType = new IndexPageType();
$indexType->templateFile = 'index_template.html';
$indexType->source_dir = '';
$indexType->output_dir = '';
$indexType->class = '\IndexPage';

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

        $this->type = $indexType;
    }
}
