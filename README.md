# Introduction

A minimalist site generator for mostly static content, intended for a blog type site.

## Content

Put your content into the `site/` folder and posts into `site/posts/`. The generator pulls all settings from the `generator.inc.php` file. This should also include your list of posts.

Look at the existing example (site-template) if you want more details, or just copy it as the `site` folder and adjust as needed.

Run `php generate.php` to generate the site.

## Composer

Composer packages are not required to be installed, because we only use one for markdown, and unless you include that explicitly, it'll work without it (and without markdown support).

## Modules

### Markdown

If you want the ability to use markdown for pages/posts include the `require_once include_module('md_page');` or `require_once include_module('md_post');` script in your `generator.inc.php` file. Currently not possible to customize the markdown css.

Also don't forget to install composer packages via `composer install` because we need the `league/markdown` package for this.

### Atom

If you want the ability to generate an atom feed, include the `require_once include_module('atom');` line in your `generator.inc.php` file.

### Tidy

If you want to tidy up your html, you can use the `tidy` module by including the `require_once include_module('tidy');` in your `generator.inc.php` file. It will automatically tidy your pages. You will need to install `php-tidy` or equivalent for your platform.

You can configure it further via the `TidyModule::$configuration` property. See [configuration options](http://tidy.sourceforge.net/docs/quickref.html) here.
