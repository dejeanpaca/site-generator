<?php

class TidyPModule extends Module
{
    /** @var TidyPModule */
    public static $module;

    public static $arguments = '-q';

    public function __construct() {
        $this->name = 'tidyp';

        parent::__construct();
    }

    public function Done() {
        foreach(Pages::$list as $page) {
            $fn = $page->getTargetFn();

            writeln('tidyp: ' . $fn);

            exec('tidyp -m ' . self::$arguments . ' ' . escapeshellarg($fn));
        }
    }
}

TidyPModule::$module = new TidyPModule();
