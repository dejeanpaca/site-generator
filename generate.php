<?php

require_once __DIR__ . '/lib.php';
require_once __DIR__ . '/utils.inc.php';
require_once __DIR__ . '/css.inc.php';
require_once __DIR__ . '/common.inc.php';
require_once __DIR__ . '/post.inc.php';
require_once __DIR__ . '/posts.inc.php';
require_once __DIR__ . '/site/generator.inc.php';

if(!is_cli()) {
   die();
}

const POST_ENTRY_TEMPLATE_FILE = 'post_entry_template.html';
const INDEX_FILE = 'index.html';

writeln("Platform: " . PHP_OS . ", PHP v" . phpversion());

$index = "";
$entry_template = "";
$put_content = "";
$css_content = "";

writeln("Generating site ...");

Post::LoadTemplate();
Common::Load();
CSS::Load();

if(is_dir(Common::$target)) {
    if(!rmTree(Common::$target)) {
        fail('Could not remove existing output directory');
    }
}

if(!mkdir(Common::$target)) {
    fail('Could not create output directory');
}

if(!mkdir(Common::$target . 'posts')) {
    fail('Could not generate posts directory in output');
}

foreach (Common::$copy_list as $what) {
    $source = Common::$source . $what;

    if(!smartCopy($source, Common::$target . $what)) {
        fail('Could not copy: ' . $source);
    }
}

$index = load_file(Common::$source . INDEX_FILE);
$entry_template = load_file(Common::$source . POST_ENTRY_TEMPLATE_FILE);

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

write_file(Common::$target . INDEX_FILE, $index);

writeln('Done');
