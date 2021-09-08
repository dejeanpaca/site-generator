<?php

if(is_dir('vendor'))
    require_once __DIR__ . '/vendor/autoload.php';

require_once __DIR__ . '/source/lib.php';
require_once __DIR__ . '/source/utils.inc.php';
require_once __DIR__ . '/source/markers.inc.php';
require_once __DIR__ . '/source/module.inc.php';
require_once __DIR__ . '/source/common.inc.php';
require_once __DIR__ . '/source/page_type.inc.php';
require_once __DIR__ . '/source/pages.inc.php';

if(!is_cli())
   die();

const POST_ENTRY_TEMPLATE_FILE = Replacer::TEMPLATE_SOURCE . 'post_entry_template.html';

writeln("Platform: " . PHP_OS . ", PHP v" . phpversion());

// check if we have a site folder
if(!is_dir(Base::$source))
    fail('No site folder found. You can copy over existing `site-template` as `site` and work from there');

function parse_arguments() {
    global $argv, $argc;
    
    foreach($argv as $v) {
        if($v == '-draft') {
            Common::$draftMode = true;
            writeln('> Draft mode');
        }
    }
}

parse_arguments();

/** simple mechanism to include a module script */
function include_module(string $module) {
    $fn = __DIR__ . '/source/modules/' . $module . '.inc.php';

    if (!file_exists($fn))
        fail('Could not find required module ' . $module);

    return $fn;
}

/** compare two properties */
function compare($a, $b) {
    if($a > $b)
        return 1;
    else if($a < $b)
        return -1;
    else
        return 0;
}

/** POST PROCESSING CALLBACK METHODS */

// load a given post (callback)
function load_post($post, $post_index) {
    $post->Load();
}

/** generate a given post (callback) */
function generate_post($post, $post_index) {
    global $entry_template;

    if($post->isDraft())
        return;

    $post->Generate();
    writeln('Generated: (' . $post_index . ') ' . $post->source);

    if($post->type->category) {
        $entry = substr($entry_template, 0);

        $link = $post->correctExtension($post->getLink());

        $entry = str_replace('__DATE__', $post->getDate(), $entry);
        $entry = str_replace('__HREF__', $link, $entry);
        $entry = str_replace('__TITLE__', $post->title, $entry);

        $cat = Common::FindCategory($post->type->category);

        if($cat)
            $cat->entries = $entry . $cat->entries;

        writeln('Entry added: ' . $post->getSourceFn() . ' to category ' . $cat->name);
    }

    foreach (Module::$modules as $module) {
        $module->OnPost($post);
    }
}

/** generate post in another pass (callback) */
function post_second_pass($post, $post_index) {
    global $entry_template;

    if($post->isDraft())
        return;

    $post->Generate();
    writeln('Second pass: (' . $post_index . ') ' . $post->source);
}

/** write the post (callback) */
function write_post($post, $post_index) {
    if($post->isDraft())
        return;
    
    if(!$post->Write())
        fail('Could not write post: ' . $post->source);
}

/** call module OnPostDone() method on a post when done */
function module_post_done($post, $post_index) {
    if($post->isDraft())
        return;

    foreach (Module::$modules as $module) {
        $module->OnPostDone($post);
    }
}

/** create output directory for posts */
function make_post_directories($post, $post_index) {
    if($post->isDraft())
        return;

    $path_info = pathinfo($post->getTargetFn());

    // create directory if required
    $dir = $path_info['dirname'];

    if($dir != '.') {
        // create directory if it doesn't already exists
        if (!file_exists($dir)) {
            writeln('Creating directory: ' . $dir);
            mkdir($dir, 0755, true);
        }
    }
}

/** go through posts in order with a callback that is passed the post and index*/
function process_posts($callback) {
    $post = null;
    $post_index = 1;

    foreach(Pages::$list as $post) {
        if($callback)
            call_user_func($callback, $post, $post_index);

        ++$post_index;
    }

}

/** GENERATOR */

// we need a generator.inc.php script for the site
if(!is_file(Base::$source . 'generator.inc.php' ))
    fail('No site definition file found (generator.inc.php).');

writeln("Generating site ...");

require_once __DIR__ . DIRECTORY_SEPARATOR . Base::$source . 'generator.inc.php';

// load entry template
$entry_template = load_file(Base::$source . POST_ENTRY_TEMPLATE_FILE);

// load page templates
foreach(PageType::$types as $type) {
    $type->LoadTemplate();
}

// load common resources
Common::Load();

// load modules
foreach (Module::$modules as $module) {
    $module->Load();
}

// load pages
foreach(PageType::$types as $type) {
    $type->Load();
}

// create target directory
recreate_directory(Base::$target);

// go through each page type and create target directory
foreach (PageType::$types as $type) {
    if($type->output_dir) {
        $target = Base::$target . $type->output_dir;

        if(!is_dir($target)) {
            if(!mkdir($target, 0777, true))
                fail('Could not generate ' . $target . ' directory in output');
            else
                writeln('Created ' . $target . ' directory in output');
        }
    }
}

// copy everything indicated in the copy list (site resources)
foreach (Common::$copy_list as $what) {
    $source = Base::$source . $what;

    copy_recursively($source, Base::$target . $what);
}


if(Common::$static)
    copy_recursively(Base::$source . '\\' . Common::$static, Base::$target);

/** POST PROCESSING */

// load all posts
process_posts('load_post');

// sort pages by index and date
usort(Pages::$list, function ($a, $b) {
    if($a->zIndex == $b->zIndex)
        return compare($a->date, $b->date);
    else
        return compare($a->zIndex, $b->zIndex);

    return 0;
});

// generate all posts
process_posts('generate_post');
// second pass
process_posts('post_second_pass');
// call module OnPostDone() method on posts
process_posts('module_post_done');
// make output directories for all posts
process_posts('make_post_directories');
// write out all posts
process_posts('write_post');

// finalize each module
foreach (Module::$modules as $module) {
    $module->Done();
}

// we're done here
writeln('Done');
