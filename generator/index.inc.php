<?php

$indexType = new IndexPageType();
$indexType->templateFile = 'index_template.html';
$indexType->output_dir = '';
$indexType->class = '\IndexPage';

class IndexPageType extends PageType
{
    public function Load() {

    }
}

class IndexPage extends Page
{
    function __construct() {
        global $indexType;

        $this->type = $indexType;
    }
}
