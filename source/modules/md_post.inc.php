<?php

$mdPostType = new PageType();
$mdPostType->templateFile = 'post_template.html';
$mdPostType->source_dir = 'md_posts' . DIRECTORY_SEPARATOR;
$mdPostType->output_dir = 'md_posts' . DIRECTORY_SEPARATOR;
$mdPostType->category = 'posts';
