# Convert WordPress Plugin Readme Files to GitHub Flavored Markdown

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
$markdown = \SunChaser\WP2MD\Converter::Convert($readme);
```

### Installation

Add a composer dependency to your project:

    "require-dev": {
        "sunchaser/wp2md": "*"
    }

The binary will be ```vendor/bin/wp2md```

Or install globally

    php composer.phar global require sunchaser/wp2md *
