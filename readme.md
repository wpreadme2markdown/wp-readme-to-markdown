Convert WordPress Plugin Readme Files to GitHub Flavored Markdown
==================================================================

Features
--------

* Converts headings
* Formats contributors, donate link, etc.
* Inserts screenshots

Usage
-----

1. Open in browser
2. Select `readme.txt` file from your plugin
3. Browser will return you `readme.md` to place in your plugin's directory


Command line
------------

### Installation

This requires you to have the cli version of php installed. Save the raw index.php file from github as 'wp-readme-to-markdown' inside a folder in your PATH and make it executable. Finally add `#!/usr/bin/php` to the first line to make the system automatically execute this script with php.

On Ubuntu and friends this can be done fast with this commands. (Apart from apt-get this should work on all kinds of unix like systems)
```
sudo apt-get install php5-cli
sudo wget https://raw.githubusercontent.com/benbalter/WP-Readme-to-Github-Markdown/master/index.php -O /usr/local/bin/wp-readme-to-markdown
sudo chmod a+x /usr/local/bin/wp-readme-to-markdown
sudo sed -i '1i#!/usr/bin/php' /usr/local/bin/wp-readme-to-markdown
```

### Command line Usage

There is just one argument and this is the input file. The output file is hardcoded to `readme.md`

`wp-readme-to-markdown readme.txt` or `wp-readme-to-markdown README.txt`

Online
------

gordon@vixo.com hosts this [online](http://wordpress-markdown-to-github-markdown.com/) so you can just convert your readme's on the go.
