<?php

$indexType = new PageType();
$indexType->templateFile = 'index_template.html';
$indexType->output_dir = '';

class IndexPage extends Page
{
    function __construct() {
        global $indexType;

        $this->type = $indexType;
    }
}
