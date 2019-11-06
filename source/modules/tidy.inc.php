<?php

class TidyModule extends Module
{
    /** @var TidyModule */
    public static $module;
    /** @var [] */
    public static $configuration;
    public static $encoding = 'utf8';

    public function __construct() {
        $this->name = 'tidy';

        self::$configuration = [
            'indent' => false
        ];

        parent::__construct();
    }

    public function Done() {
        foreach(Pages::$list as $page) {
            $fn = $page->getTargetFn();

            writeln('Tidy: ' . $fn);

            $tidy = new tidy($fn, self::$configuration, self::$encoding);

            if($tidy->cleanRepair()) {
                $errors = $tidy->errorBuffer;

                if($errors)
                    writeln($errors);

                $output = tidy_get_output($tidy);
                $page->generated = $output;
                $page->Write(true);
            } else
                writeln('Failed to tidy: ' . $fn);
        }
    }
}

TidyModule::$module = new TidyModule();
