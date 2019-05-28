<?php

function fail($what) {
    die($what);
}

function load_file($fn) {
    $content = @file_get_contents($fn);

    if(!$content) {
        fail('Could not read: ' . $fn);
    }

    return $content;
}
