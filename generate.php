<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/generator/lib.php';
require_once __DIR__ . '/generator/utils.inc.php';
require_once __DIR__ . '/generator/css.inc.php';
require_once __DIR__ . '/generator/common.inc.php';
require_once __DIR__ . '/generator/page_type.inc.php';
require_once __DIR__ . '/generator/pages.inc.php';

if(!is_cli()) {
   die();
}

const POST_ENTRY_TEMPLATE_FILE = Replacer::TEMPLATE_SOURCE . 'post_entry_template.html';

writeln("Platform: " . PHP_OS . ", PHP v" . phpversion());

if(!is_dir(Common::$source)) {
    fail('No site folder found. You can copy over existing `site-template` as `site` and work from there');
}

if(!is_file(Common::$source . 'generator.inc.php' )) {
    fail('No site definition file found (generator.inc.php).');
}

writeln("Generating site ...");

require_once __DIR__ . DIRECTORY_SEPARATOR . Common::$source . 'generator.inc.php';

$structure = [Common::$target];
$entry_template = "";
$css_content = "";

foreach(PageType::$types as $type) {
    $type->LoadTemplate();
}

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

foreach (PageType::$types as $type) {
    if($type->output_dir) {
        $target = Common::$target . $type->output_dir;

        if(!is_dir($target)) {
            if(!mkdir($target)) {
                fail('Could not generate ' . $target . ' directory in output');
            } else {
                writeln('Created ' . $target . ' directory in output');
            }
        }
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

        if($post->post) {
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
