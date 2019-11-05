<?php

class AtomModule extends Module
{
    public const TEMPLATES_PATH = 'templates' . DIRECTORY_SEPARATOR . 'atom' . DIRECTORY_SEPARATOR;
    public const FEED_FILE_NAME = 'feed.atom';

    /** @var AtomModule */
    public static $module;

    /** @var Replacer */
    public $feed_template = null;
    /** @var Replacer */
    public $entry_template = null;

    public $entries = [];

    /** latest date found in any post (to base feed update time on) */
    public $lastDate = null;

    public function __construct() {
        $this->name = 'Atom';

        parent::__construct();
    }

    public function Load() {
        $this->feed_template = new Replacer();
        $this->feed_template->file_path = self::TEMPLATES_PATH . 'feed.atom';
        $this->feed_template->Load();

        $this->entry_template = new Replacer();
        $this->entry_template->file_path = self::TEMPLATES_PATH . 'entry.atom';
        $this->entry_template->Load();
    }

    /** @param Page $post */
    public function OnPost($post) {
        // only process pages which have a feed uuid marker
        if(!$post->markers->Has('__FEED_UUID__'))
            return;

        if($post->date) {
            if($this->lastDate) {
                if($post->date > $this->lastDate)
                    $this->lastDate = $post->date;
            } else
                $this->lastDate = $post->date;
        }

        $entry = substr($this->entry_template->content, 0);

        // set time updated if none
        if(!$post->markers->Has('__TIME_UPDATED__')) {
            $entry = str_replace('__TIME_UPDATED__', $post->getDate(), $entry);
        }

        // set empty summary if none
        $entry = str_replace('__SUMMARY__', $post->summary, $entry);

        $generated_link = false;

        // set empty summary if none
        if(!$post->markers->Has('__LINK__')) {
            if(Common::$markers->Has('__SITE_LINK__'))  {
                $link = str_replace('\\', '/', $post->getLink());

                $link = Common::$markers->Get('__SITE_LINK__') . '/' . $link;

                $entry = str_replace('__LINK__', $link, $entry);
                $generated_link = true;
            }
        }

        if(!$generated_link)
            writeln('Could not generate feed link for: ' . $post->getDescriptor());

        $entry = $post->Inject($entry);

        array_push($this->entries, $entry);
    }

    public function Done() {
        $feed = substr($this->feed_template->content, 0);
        $feed = Common::Inject($feed);

        $entries = '';

        foreach ($this->entries as $entry) {
            $entries = $entries . $entry;
        }

        $feed = str_replace('__FEED_ENTRIES__', $entries, $feed);

        $updated_time = '';
        if($this->lastDate)
            $updated_time = date("c", $this->lastDate);

        $feed = str_replace('__FEED_TIME_UPDATED__', $updated_time, $feed);

        $fn = Base::$target . self::FEED_FILE_NAME;

        $ok = write_file($fn, $feed, false);

        if(!$ok)
            fail('Failed to write feed file: ' . $fn);
    }
}

AtomModule::$module = new AtomModule();
