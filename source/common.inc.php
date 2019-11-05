<?php

require_once __DIR__ . '/base.inc.php';
require_once __DIR__ . '/category.inc.php';
require_once __DIR__ . '/replacer.inc.php';

class Common
{
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
        self::AddCategory(Category::Create('posts', '__POST_LIST__'));
    }

    public static function AddCategory($category) {
        if(!self::FindCategory($category))
            array_push(self::$categories, $category);
    }

    public static function CreateCategory($name, $marker) {
        Self::AddCategory(Category::Create($name, $marker));
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

        // categories
        foreach (Common::$categories as $cat) {
            $string = $cat->Inject($string);
        }

        return $string;
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
