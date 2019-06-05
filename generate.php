<?php

require_once __DIR__ . '/lib.php';
require_once __DIR__ . '/utils.inc.php';
require_once __DIR__ . '/css.inc.php';
require_once __DIR__ . '/common.inc.php';
require_once __DIR__ . '/page_type.inc.php';
require_once __DIR__ . '/page.inc.php';
require_once __DIR__ . '/post.inc.php';
require_once __DIR__ . '/index.inc.php';
require_once __DIR__ . '/pages.inc.php';

if(!is_cli()) {
   die();
}

const POST_ENTRY_TEMPLATE_FILE = 'post_entry_template.html';

writeln("Platform: " . PHP_OS . ", PHP v" . phpversion());

if(!is_dir('site')) {
    fail('No site folder found. You can copy over existing `site-template` as `site` and work from there');
}

writeln("Generating site ...");

require_once __DIR__ . '/site/generator.inc.php';

$structure = ['output', 'output/posts'];
$entry_template = "";
$css_content = "";

$postType->LoadTemplate();
$pageType->LoadTemplate();
$indexType->LoadTemplate();

$target = Common::$target . 'posts';

Common::Load();
CSS::Load();

function create_directory($target) {
    if(is_dir($target)) {
        if(!rmTree($target)) {
            fail('Could not remove existing ' . $target . ' directory');
        }
    }

    if(!mkdir($target)) {
        fail('Could not create ' . $target . ' directory');
    }
}

foreach ($structure as $folder) {
    create_directory($folder);
}

if(!is_dir($target)) {
    if(!mkdir($target)) {
        fail('Could not generate posts directory in output');
    }
}

foreach (Common::$copy_list as $what) {
    $source = Common::$source . $what;

    if(!smartCopy($source, Common::$target . $what)) {
        fail('Could not copy: ' . $source);
    }
}

$entry_template = load_file(Common::$source . POST_ENTRY_TEMPLATE_FILE);

function load_post($post, $post_index) {
    $post->Load();
}

function generate_post($post, $post_index) {
    global $entry_template;

    if($post->Generate()) {
        writeln('Generated: (' . $post_index . ') ' . $post->source);

        if(get_class($post) == 'Post') {
            $entry = substr($entry_template, 0);

            $entry = str_replace('__DATE__', $post->getDate(), $entry);
            $entry = str_replace('__HREF__', "/posts/" . $post->source, $entry);
            $entry = str_replace('__TITLE__', $post->title, $entry);

            Common::$post_list = $entry . Common::$post_list;
        }
    } else {
        fail('Could not generate post: ' . $post->source);
    }
}

function order_posts($callback) {
    $post = null;
    $post_index = 1;

    foreach(Pages::$list as $post) {
        if($callback) {
            call_user_func($callback, $post, $post_index);
        }

        ++$post_index;
    }
}

order_posts('load_post');
order_posts('generate_post');

writeln('Done');
