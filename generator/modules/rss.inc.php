<?php

class RSS extends Module
{
    public static $generate = False;

    public function __construct() {
        $this->name = 'RSS';

        parent::__construct();
    }

    public function OnPost($post) {

    }
}

$rss = new RSS();
