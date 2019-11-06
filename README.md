# Introduction

A minimalist site generator for mostly static content, intended for a blog type site.

## Content

Put your content into the `site/` folder and posts into `site/posts/`. The generator pulls all settings from the `generator.inc.php` generator config file.

Look at the existing example (site-template) if you want more details, or just copy it as the `site` folder and adjust as needed.

Run `php generate.php` to generate the site.

## Composer

Composer packages are not required to be installed, because we only use one for markdown, and unless you include that explicitly, it'll work without it (and without markdown support)
.

## Modules

Using modules. You can include a module with the help of the `include_module($module_name)` helper function in your generator config, e.g. `require_once include_module('tidy');`.

## Markers

You can configure a marker in your generator config, which serves to replace keywords in pages with some string.

`Common::$markers->Add('__AUTHOR_NAME__', 'Your name');`

### Markdown

Module: `md_page`, `md_post`

Currently not possible to customize the markdown css.

Also don't forget to install composer packages via `composer install` because we need the `league/markdown` package for this.

### Atom

Module: `atom`

You'll need to configure the following markers in your generator config.

- `__AUTHOR_NAME__`
- `__SITE_LINK__`
- `__FEED_TITLE__`
- `__FEED_UUID__`

### Tidy

Module: `tidy`

It will automatically tidy your pages. You will need to install `php-tidy` or equivalent for your platform.

You can configure it further via the `TidyModule::$configuration` property. See [configuration options](http://tidy.sourceforge.net/docs/quickref.html) here.
