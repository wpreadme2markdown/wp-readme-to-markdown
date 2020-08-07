# Changelog

## 3.1.0

* Normalized and upgraded generated markdown

## 3.0.1

* Support for new "Requires PHP" header
  (by [@florianbrinkmann])

## 3.0.0

* CLI tool is moved to its own repo and can be installed from packagist as `wpreadme2markdown/wp2md`

## 2.0.2

* Fixed relative file paths resolution in phar
  (with help from [@inderpreet99])
* Fixed broken screenshot detection
  (by [@inderpreet99])
* Correct handling of files with DOS line endings
* New phar compilation tool and script, phar binary is 75% smaller now

## 2.0.1

* WooCommerce specific tags added
  (by [@jamesgol])
* CLI tool now allows using Symfony 3 console
* Fixed incorrect header conversion after some lists

Note: published wp2md.phar now requres PHP >= 5.5.9. However you still can compile your phar with PHP >= 5.3.3 if required

## 2.0.0

Merge of WP Readme to Github Markdown and WP2MD \
Continue the version numbering for WP2MD \
As namespace and package name changed, mark it as a new major version

[@florianbrinkmann]: https://github.com/florianbrinkmann
[@inderpreet99]: https://github.com/inderpreet99
[@jamesgol]: https://github.com/jamesgol
