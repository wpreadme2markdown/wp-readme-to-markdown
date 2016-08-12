# WP Readme to Markdown

Convert WordPress Plugin Readme Files to GitHub Flavored Markdown

## Features

* Converts headings
* Formats contributors, donate link, etc.
* Inserts screenshots

## Usage

CLI:

    # with files as params
    wp2md convert -i readme.txt -o README.md
    # or with unix pipes
    wp2md convert < readme.txt > README.md


PHP:

```php
$markdown = \WPReadme2Markdown\Converter::convert($readme);
```

## Installation

### Composer (recommended)

Add a composer dependency to your project:

    "require-dev": {
        "wpreadme2markdown/wpreadme2markdown": "*"
    }

The binary will be `vendor/bin/wp2md`

### Download binary

You may install WP2MD binary globally

    sudo wget https://github.com/wpreadme2markdown/wp-readme-to-markdown/releases/download/2.0.2/wp2md.phar -O /usr/local/bin/wp2md
    sudo chmod a+x /usr/local/bin/wp2md

## PHAR compilation

    # install dependencies
    composer install
    # run pake build script
    composer pake phar

Executable PHAR archive will be created as `build/wp2md.phar`

* This assumes composer is installed as a package in your operating system.
  If not, replace `composer` with php command and your composer.phar location
  (i.e. `php ../phars/composer.phar`)

## Online

Try the online version on [wpreadme2markdown.snch.im](https://wpreadme2markdown.snch.im/)
