php-mf2-shim
============

An extension for [php-mf2](https://github.com/indieweb/php-mf2) which screen-scrapes some sites which don’t support [microformats2](http://microformats.org/wiki/microformats2) into canonical microformats data structures to make them easy to consume.

Supported sites:

* **twitter.com** — tweets are parsed as h-entries with h-card authors, replies to tweets on permalink pages are parsed
* **facebook.com** — post permalink pages are parsed as h-entries with h-card authors

Work-in-progress:

* instagram.com — no support right now

## Installation

Install php-mf2-shim with Composer by adding "mf2/shim": "0.2.*" to the require object in your composer.json and running `php composer.phar update`.

You could install it by just downloading php-mf2, /Mf2/functions.php, /Mf2/Shim/*.php and including those, but please use Composer. Seriously, it’s amazing.

## Usage

mf2-shim is PSR-0 autoloadable, so all you have to do to load it is:

* Include Composer’s auto-generated autoload file (/vendor/autoload.php)
* Call Mf2\Shim\parseTwitter() or parseFacebook() with the HTML (or a DOMDocument), and optionally the URL to resolve relative URLs against.

## Examples

```php
<?php

require 'vendor/autoload.php';

use Mf2;

$output = Mf2\Shim\parseTwitter($html, $url);
$output = Mf2\Shim\parseFacebook($html, $url);

```

## Changelog

### v0.2.4

* Improved pre-processing of in-tweet links, removing twitter gunk and t.co URLs for cleaner content.

### v0.2.3
* TODO: what happened in this update? It exists but the changelog wasn’t updated.

### v0.2.2

* Twitter parsing improved, now parses profile h-card and h-entries successfully from profile pages

### v0.2.1

* Added first draft of support for Facebook post permalink shimming

### v0.2.0 (BREAKING)

* Restructured things for consistency with php-mf2 v0.2.0
* Removed dependencies, now only depends on php-mf2
* Twitter parsing code tweet content is now an e-* dict containing html and value keys with raw and plaintext values, respectively

### v0.1.0 (2013-06-07)

* Initial tagged release
* MIT Licenesed
* Twitter tests passing, Instagram test failing
* Tweet permalink page parsing working including authors and reply tweets
