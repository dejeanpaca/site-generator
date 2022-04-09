<?php

$indexType = new IndexPageType();
$indexType->templateFile = 'index_template.html';
$indexType->SetDirectory('');
$indexType->zIndex = 100000;

class IndexPageType extends PageType
{
    // we'll only load the index page
    public function Load() {
        $index_page = $this->InstancePage();

        $index_page->source = 'index.html';

        Pages::add($index_page);
    }
}
