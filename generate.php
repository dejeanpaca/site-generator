<?php

require_once __DIR__ . '/lib.php';

if(!is_cli()) {
   die();
}

function fail($what) {
    die($what);
}

$target = 'output' . DIRECTORY_SEPARATOR ;

$copy_list = ['css', 'fonts', 'js', 'index.html'];
$content = "";

writeln("Generating site (" . PHP_OS . ", PHP v" . phpversion() . ") ");

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

