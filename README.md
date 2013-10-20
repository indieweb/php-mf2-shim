php-mf2-shim
============

A parsing library for parsing pages from a few common non-mf2 sites into the mf2 structure.

## Installation

Install php-mf2-shim with Composer by adding "mf2/shim": "0.2.*" to the require object in your composer.json and running `php composer.phar update`.

You could install it by just downloading /Mf2/functions.php, /Mf2/Shim/*.php and including those, but please use Composer. Seriously, it’s amazing.

## Usage

mf2-shim is PSR-0 autoloadable, so all you have to do to load it is:

* Include Composer’s auto-generated autoload file (/vendor/autoload.php)
* Call Mf2\parseTwitter() with the HTML (or a DOMDocument), and optionally the URL to resolve relative URLs against.

## Examples

```php
<?php

require 'vendor/autoload.php';

use Mf2;

$output = Mf2\Shim\parseTwitter($html, $url);
```

## Changelog

### v0.2.0 (BREAKING)

* Restructured things for consistency with php-mf2 v0.2.0
* Removed dependencies, now only depends on php-mf2
* Twitter parsing code tweet content is now an e-* dict containing html and value keys with raw and plaintext values, respectively

### v0.1.0 (2013-06-07)

* Initial tagged release
* MIT Licenesed
* Twitter tests passing, Instagram test failing
* Tweet permalink page parsing working including authors and reply tweets