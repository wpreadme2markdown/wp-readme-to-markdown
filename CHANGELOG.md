# Changelog

## 4.1.1

* Fixed image detection broken by [#32]

## 4.1.0

* Added an option to skip the image check [#32]
  (by [@LC43])

## 4.0.3

* Fixed first header not being converted since 4.0.2

## 4.0.2

* Fixed equal sign becoming a header when not in the beginning of the line [#28]
  (by [@msaari], [@evrpress])

## 4.0.1

* Fixed crash when plugin slug is null

## 4.0.0

* Dropped support for PHP earlier than 7.2
* Fixed screenshot discovery [#27]

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

Note: published wp2md.phar now requires PHP >= 5.5.9, however you still can compile your phar with PHP >= 5.3.3 if required.

## 2.0.0

Merge of WP Readme to Github Markdown and WP2MD \
Continue the version numbering for WP2MD \
As namespace and package name changed, mark it as a new major version

[@florianbrinkmann]: https://github.com/florianbrinkmann
[@inderpreet99]: https://github.com/inderpreet99
[@jamesgol]: https://github.com/jamesgol
[@msaari]: https://github.com/msaari
[@evrpress]: https://github.com/evrpress
[@LC43]: https://github.com/LC43

[#27]: https://github.com/wpreadme2markdown/wp-readme-to-markdown/issues/27
[#28]: https://github.com/wpreadme2markdown/wp-readme-to-markdown/issues/28
[#32]: https://github.com/wpreadme2markdown/wp-readme-to-markdown/pull/32
