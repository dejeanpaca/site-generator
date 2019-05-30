<?php

$postType = new PageType();
$postType->templateFile = 'post_template.html';
$postType->source_dir = 'posts/';
$postType->output_dir = 'posts/';

class Post extends Page
{
    function __construct() {
        global $postType;

        $this->type = $postType;
    }
}
