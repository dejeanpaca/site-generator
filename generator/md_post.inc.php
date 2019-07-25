<?php

$mdPostType = new PageType();
$mdPostType->templateFile = 'post_template.html';
$mdPostType->source_dir = 'posts' . DIRECTORY_SEPARATOR;
$mdPostType->output_dir = 'posts' . DIRECTORY_SEPARATOR;
$mdPostType->post = true;

class MarkdownPost extends MarkdownPage
{
    function __construct() {
        global $mdPostType;

        $this->type = $mdPostType;
    }
}
