# WP Readme to Markdown

[![Packagist](https://img.shields.io/packagist/v/wpreadme2markdown/wpreadme2markdown.svg?maxAge=2592000)](https://packagist.org/packages/wpreadme2markdown/wpreadme2markdown)
[![Code Climate](https://img.shields.io/codeclimate/github/wpreadme2markdown/wp-readme-to-markdown.svg?maxAge=2592000)](https://codeclimate.com/github/wpreadme2markdown/wp-readme-to-markdown)

Convert WordPress Plugin Readme Files to GitHub Flavored Markdown

## Features

* Converts headings
* Formats contributors, donate link, etc.
* Inserts screenshots

## Usage

```php
$markdown = \WPReadme2Markdown\Converter::convert($readme);
```

## Installation

### Composer (recommended)

Add a composer dependency to your project:

    "require-dev": {
        "wpreadme2markdown/wpreadme2markdown": "*"
    }

## CLI Version

Visit [this GitHub page](https://github.com/wpreadme2markdown/wp2md) for the CLI version

## Web Version

Visit [this GitHub page](https://github.com/wpreadme2markdown/web) for the web version and a link to its running instance
