# WP Readme to Markdown

[![Packagist](https://img.shields.io/packagist/v/wpreadme2markdown/wpreadme2markdown.svg?maxAge=2592000)](https://packagist.org/packages/wpreadme2markdown/wpreadme2markdown)

> The project is retired, my main reasons are:
> * I no longer use WordPress and develop plugins for it
> * I now agree with the idea that GitHub's readme and WordPress readme serve different purpose
>   and should therefore be different
> * I see an effort from WordPress to prevent some features of this tool from working (screenshot detection)

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

### Composer

Add a composer dependency to your project:

```
composer require wpreadme2markdown/wpreadme2markdown --dev
```

## CLI Version

Visit [this GitHub page](https://github.com/wpreadme2markdown/wp2md) for the CLI version

## Web Version

Visit [this GitHub page](https://github.com/wpreadme2markdown/web) for the web version and a link to its running instance
