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

    sudo wget http://code.sunchaser.info/wp2md/downloads/wp2md.phar -O /usr/local/bin/wp2md
    sudo chmod a+x /usr/local/bin/wp2md

## PHAR compilation

    # install composer
    curl -sS https://getcomposer.org/installer | php
    # install dependencies
    php composer.phar install
    # run pake build script
    php composer.phar pake phar

Executable PHAR archive will be created as `build/wp2md.phar`

## Online

Try the online version on [wp2md.snch.im](https://wp2md.snch.im/)
