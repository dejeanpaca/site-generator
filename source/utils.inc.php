<?php

function fail($what) {
    die('ERROR: ' . $what . PHP_EOL);
}

function load_file($fn, $fail = true) {
    $content = @file_get_contents($fn);

    if(!$content) {
        if($fail)
            fail('Could not read: ' . $fn);
        else
            writeln('Could not read: ' . $fn);
    } else
        writeln('Read: ' . $fn);

    return $content;
}

function load_file_optional($fn, $fail = true) {
    if(file_exists($fn))
        return load_file($fn);

    return "";
}

function write_file($fn, $contents, $fail = true) {
    if(!@file_put_contents($fn, $contents)) {
        if($fail)
            fail('Failed to write: ' . $fn);
        else
            writeln('Failed to write: ' . $fn);

        return false;
    } else
        writeln('Written: ' . $fn);

    return true;
}

/** creates a directory by first removing the target directory, and recreating it */
function recreate_directory($target) {
    if(is_dir($target))
        rmTree($target);

    if(!mkdir($target, 0777, true))
        fail('Could not create ' . $target . ' directory');
}
