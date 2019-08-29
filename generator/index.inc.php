<?php

$indexType = new SingletonPageType();
$indexType->templateFile = 'index_template.html';
$indexType->output_dir = '';
$indexType->class = '\IndexPage';

class IndexPage extends Page
{
    function __construct() {
        global $indexType;

        $this->type = $indexType;
    }
}
