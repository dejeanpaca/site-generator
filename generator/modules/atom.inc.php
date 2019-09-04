<?php

class AtomModule extends Module
{
    public const TEMPLATES_PATH = 'templates' + DIRECTORY_SEPARATOR + 'atom' + DIRECTORY_SEPARATOR;

    /** @var AtomModule */
    public static $atom = null;

    /** @var Replacer */
    public $feed_template = null;
    /** @var Replacer */
    public $entry_template = null;

    public function __construct() {
        $this->name = 'Atom';

        parent::__construct();
    }

    public function Load() {
        $this->$feed_template = new Replacer();
        $this->$feed_template->file_path = self::TEMPLATES_PATH + 'feed.atom';
        $this->$entry_template = new Replacer();
        $this->$entry_template->file_path = self::TEMPLATES_PATH + 'entry.atom';
    }

    public function OnPost($post) {
    }
}

AtomModule::$atom = new AtomModule();
