# Introduction

A minimalistic site generator for mostly static content, intended for a blog type site.

## Content

Put your content into the `site/` folder and posts into `site/posts/`. The generator pulls all settings from the `generator.inc.php` file. This should also include your list of posts.

Look at the existing example (site-template) if you want more details, or just copy it as the `site` folder and adjust as needed.

Run `php generate.php` to generate the site.

## Composer

Composer packages are not required to be installed, because we only use one for markdown, and unless you include that explicitly, it'll work without it (and without markdown support).

## Markdown

If you want the ability to use markdown for pages/posts include the `generator/md_pages.inc.php` script in your `generator.inc.php` file and use MDPages class to add pages and posts. Currently not possible to customize the markdown css.

Also don't forget to install composer packages via `composer install` because we need the `league/markdown` package for this.
