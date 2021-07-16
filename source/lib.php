<?php

function is_cli() {
    return \PHP_SAPI == "cli";
}

// collection of helper methods

$stdout = fopen("php://stdout", "w");
$is_windows = PHP_OS == 'WINNT';

function writeout($what) {
    global $stdout;

    fwrite($stdout, $what);
}

function writeln($what) {
    writeout($what . "\n");
}

function echoln($what) {
    echo $what . "\n";
}

function copy_recursively($src, $dst) {
    // check if source is file
    if(is_file($src)) {
        copy($src, $dst);
        return;
    }

    // check if source exists
    if(!file_exists($src))
        return;

    $dir = opendir($src);
    @mkdir($dst);

    while (false !== ($file = readdir($dir))) {
        if (($file != '.') && ($file != '..')) {
            if (is_dir($src . '/' . $file))
                copy_recursively($src . '/' . $file, $dst . '/' . $file);
            else
                copy($src . '/' . $file, $dst . '/' . $file);
        }
    }

    closedir($dir);
}

function rmTree($dir) {
    if($dir && $dir != '/')
        shell_exec('rm -rf ' . $dir);
}
