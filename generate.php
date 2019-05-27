<?php

require_once __DIR__ . '/lib.php';
require_once __DIR__ . '/list_object.inc.php';
require_once __DIR__ . '/posts/list.inc.php';

if(!is_cli()) {
   die();
}

function fail($what) {
    die($what);
}

writeln("Platform: " . PHP_OS . ", PHP v" . phpversion());

$target = 'output' . DIRECTORY_SEPARATOR;

$copy_list = ['css', 'fonts', 'js', 'index.html'];
$content = "";

writeln("Generating site ...");

if(!Post::LoadTemplate()) {
    fail('Could not load post template');
}

if(!rmTree($target)) {
    fail('Could not remove existing output directory');
}

if(!mkdir($target)) {
    fail('Could not create output directory');
}

if(!mkdir($target . 'posts')) {
    fail('Could not generate posts directory in output');
}

foreach ($copy_list as $what) {
    if(!smartCopy($what, $target . $what)) {
        fail('Could not copy: ' . $what);
    }
}

$content = file_get_contents('index.html');

if(!$content) {
    fail('Could not read index.html');
}

$content = str_replace('__CONTENT__', $put_content, $content);

if(!file_put_contents($target . 'index.html', $content)) {
    fail('Failed to create index.html');
}

foreach(Post::$list as $post) {
    if(!$post->Load()) {
        fail('Could not load post: ' . $post->source);
    }
}

foreach(Post::$list as $post) {
    if(!$post->Generate()) {
        fail('Could not generate post: ' . $post->source);
    }
}
