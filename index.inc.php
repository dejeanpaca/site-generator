<?php

$indexType = new PageType();
$indexType->templateFile = 'index_template.html';
$indexType->source_dir = '';
$indexType->output_dir = '';

class IndexPage extends Post
{
    function __construct() {
        global $indexType;

        $this->type = $indexType;
    }
}
