<?php

require_once __DIR__ . '/base.inc.php';
require_once __DIR__ . '/category.inc.php';
require_once __DIR__ . '/replacer.inc.php';

class Common
{
    public const POST_LIST_MARKER = '__POST_LIST__';

    public static $post_list = '';
    public static $copy_list = [];
    public static $replacers = [];
    /** @var Markers */
    public static $markers = null;

    /** Different types of categories
     * @var Category[] */
    public static $categories = [];

    public static function Initialize() {
        self::$markers = new Markers();

        // add the default category
        $default = new Category();
        $default->name = 'posts';
        $default->replacer = '__POST_LIST__';
        $default->source = 'posts';

        self::AddCategory($default);
    }

    public static function AddCategory($category) {
        if(!self::FindCategory($category))
            array_push(self::$categories, $category);
    }

    public static function FindCategory($name) {
        foreach(self::$categories as $cat) {
            if($cat->name == $name)
                return $cat;
        }

        return false;
    }

    public static function Inject($string) {
        // module injection
        foreach(Module::$modules as $module) {
            $string = $module->Inject($string);
        }

        // global markers
        $string = self::$markers->Inject($string);

        // replacers
        foreach (self::$replacers as $replacer) {
            $string = $replacer->Inject($string);
        }

        return str_replace(self::POST_LIST_MARKER, self::$post_list, $string);
    }

    public static function Add($marker, $file) {
        $replacer = new Replacer();
        $replacer->marker = $marker;
        $replacer->file = $file;

        array_push(self::$replacers, $replacer);
    }

    public static function Load() {
        foreach(self::$replacers as $replacer) {
            $replacer->Load();
        }
    }
}

Common::Initialize();
