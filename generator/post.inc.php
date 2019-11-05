<?php

$postType = new PageType();
$postType->templateFile = 'post_template.html';
$postType->source_dir = 'posts' . DIRECTORY_SEPARATOR;
$postType->output_dir = 'posts' . DIRECTORY_SEPARATOR;
$postType->category = 'posts';
$postType->class = '\Post';

class Post extends Page
{
    function __construct() {
        global $postType;

        parent::__construct();

        $this->type = $postType;
    }
}
