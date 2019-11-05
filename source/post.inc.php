<?php

$postType = new PageType();
$postType->templateFile = 'post_template.html';
$postType->SetDirectory('posts');
$postType->category = 'posts';
$postType->class = '\Post';

class Post extends Page
{
    function __construct() {
        global $postType;

        parent::__construct();

        $this->setType($postType);
    }
}
