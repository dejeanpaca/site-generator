<?php

require_once __DIR__ . '/lib.php';
require_once __DIR__ . '/utils.inc.php';
require_once __DIR__ . '/header.inc.php';
require_once __DIR__ . '/css.inc.php';
require_once __DIR__ . '/common.inc.php';
require_once __DIR__ . '/post.inc.php';
require_once __DIR__ . '/posts.inc.php';
require_once __DIR__ . '/posts/list.inc.php';

if(!is_cli()) {
   die();
}

const POST_ENTRY_TEMPLATE_FILE = 'post_entry_template.html';
const INDEX_FILE = 'index.html';

writeln("Platform: " . PHP_OS . ", PHP v" . phpversion());

$target = 'output' . DIRECTORY_SEPARATOR;

$copy_list = ['css', 'fonts', 'js'];
$index = "";
$header = "";
$entry_template = "";
$put_content = "";
$css_content = "";

writeln("Generating site ...");

Post::LoadTemplate();

if(is_dir($target)) {
    if(!rmTree($target)) {
        fail('Could not remove existing output directory');
    }
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

$index = load_file(INDEX_FILE);
$entry_template = load_file(POST_ENTRY_TEMPLATE_FILE);
Header::Load();
CSS::Load();

function load_post($post, $post_index) {
    $post->Load();
}

function generate_post($post, $post_index) {
    global $put_content;
    global $entry_template;

    if($post->Generate()) {
        writeln('Generated: (' . $post_index . ') ' . $post->source);

        $entry = substr($entry_template, 0);

        $entry = str_replace('__DATE__', $post->getDate(), $entry);
        $entry = str_replace('__HREF__', "/posts/" . $post->source, $entry);
        $entry = str_replace('__TITLE__', $post->title, $entry);

        $put_content = $entry . $put_content;
    } else {
        fail('Could not generate post: ' . $post->source);
    }
}

function order_posts($callback) {
    $post = null;
    $post_index = 1;

    foreach(Posts::$list as $post) {
        if($callback) {
            call_user_func($callback, $post, $post_index);
        }

        ++$post_index;
    }
}

order_posts('load_post');
order_posts('generate_post');

$index = Common::Inject($index);
$index = str_replace('__CONTENT__', $put_content, $index);

write_file($target . INDEX_FILE, $index);

writeln('Done');
