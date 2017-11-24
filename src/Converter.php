<?php
/**
 * Converts WordPress-flavored markup from standard readme.txt files
 * to Github-flavored markup for a README.md file
 * @author Benjamin J. Balter -- http://ben.balter.com
 * @license MIT
 * @version 1.0
 */

namespace WPReadme2Markdown;

class Converter
{
    /**
     * @param string $readme plugin readme.txt content
     * @param string $pluginSlug explicitly set the plugin slug, NULL for autodetect
     * @return string
     */
    public static function convert($readme, $pluginSlug = null)
    {
        // convert line endings from DOS to Unix
        $readme = str_replace("\r\n", "\n", $readme);

        //Convert Headings
        //original code from https://github.com/markjaquith/WordPress-Plugin-Readme-Parser/blob/master/parse-readme.php
        //using here in reverse to go from WP to GitHub style headings
        $readme = preg_replace('|^=([^=]+)=*?\s*?\n|im', PHP_EOL . '###$1' . PHP_EOL, $readme);
        $readme = preg_replace('|^==([^=]+)=*?\s*?\n|im', PHP_EOL . '##$1' . PHP_EOL, $readme);
        $readme = preg_replace('|^===([^=]+)=*?\s*?\n|im', PHP_EOL . '#$1' . PHP_EOL, $readme);

        //parse contributors, donate link, etc.
        $labels = array(
            'Contributors',
            'Donate link',
            'Tags',
            'Requires at least',
            'Tested up to',
            'Requires PHP',
            'Stable tag',
            'License',
            'License URI',
            'WC requires at least',
            'WC tested up to',
        );
        foreach ($labels as $label) {
            $readme = preg_replace("|^($label): (.+)$|im", '**$1:** $2  ', $readme);
        }

        if ($pluginSlug !== null) {
            $plugin = $pluginSlug;
        } else {
            //guess plugin slug from plugin name
            preg_match('|^#(.*?)$|im', $readme, $matches);
            $plugin = str_replace(' ', '-', strtolower(trim($matches[1])));
        }

        //process screenshots, if any
        if (preg_match('|## Screenshots (.*?)## [a-z]+ |ism', $readme, $matches)) {
            //parse screenshot list into array
            preg_match_all('|^[0-9]+\. (.*)$|im', $matches[1], $screenshots, PREG_SET_ORDER);

            //replace list item with markdown image syntax, hotlinking to plugin repo
            $i = 1;
            foreach ($screenshots as $screenshot) {
                $screenshot_url = self::findScreenshot($i, $plugin);
                if ($screenshot_url) {
                    $readme = str_replace($screenshot[0], "### {$i}. {$screenshot[1]}\n![{$screenshot[1]}](" . $screenshot_url . ")\n", $readme);
                } else {
                    $readme = str_replace($screenshot[0], "### {$i}. {$screenshot[1]}\n[missing image]\n", $readme);
                }
                $i++;
            }

        }

        return ltrim($readme);
    }

    /**
     * Finds the correct screenshot file with the given number and plugin slug.
     *
     * As per the WordPress plugin repo, file extensions may be any
     * of: (png|jpg|jpeg|gif).  We look in the /assets directory first,
     * then in the base directory.
     *
     * @param   int $number Screenshot number to look for
     * @param   string $plugin_slug
     * @return  string|false   Valid screenshot URL or false if none found
     * @uses    url_validate
     * @link    http://wordpress.org/plugins/about/readme.txt
     */
    private static function findScreenshot($number, $plugin_slug)
    {
        $extensions = array('png', 'jpg', 'jpeg', 'gif');

        // this seems to now be the correct URL, not s.wordpress.org/plugins
        $base_url   = 'https://s.w.org/plugins/' . $plugin_slug . '/';
        $assets_url = 'https://ps.w.org/' . $plugin_slug . '/assets/';

        /* check assets for all extensions first, because if there's a
           gif in the assets directory and a jpg in the base directory,
           the one in the assets directory needs to win.
        */
        foreach (array($assets_url, $base_url) as $prefix_url) {
            foreach ($extensions as $ext) {
                $url = $prefix_url . 'screenshot-' . $number . '.' . $ext;
                if (self::validateUrl($url)) {
                    return $url;
                }
            }
        }

        return false;
    }

    /**
     * Test whether a file exists at the given URL.
     *
     * To do this as quickly as possible, we use fsockopen to just
     * get the HTTP headers and see if the response is "200 OK".
     * This is better than fopen (which would download the entire file)
     * and cURL (which might not be installed on all systems).
     *
     * @param    string $link URL to validate
     * @return   boolean
     * @link http://www.php.net/manual/en/function.fsockopen.php#39948
     */
    private static function validateUrl($link)
    {
        $url_parts = @parse_url($link);

        if (empty($url_parts['host'])) {
            return false;
        }
        $host = $url_parts['host'];

        if (!empty($url_parts['path'])) {
            $documentpath = $url_parts['path'];
        } else {
            $documentpath = '/';
        }

        if (!empty($url_parts['query'])) {
            $documentpath .= '?' . $url_parts['query'];
        }

        if (!empty($url_parts['port'])) {
            $port = $url_parts['port'];
        } else {
            $port = '80';
        }

        $socket = @fsockopen($host, $port, $errno, $errstr, 30);

        if (!$socket) {
            return false;
        } else {
            fwrite($socket, "HEAD " . $documentpath . " HTTP/1.0\r\nHost: $host\r\n\r\n");
            $http_response = fgets($socket, 22);

            if (preg_match('/200 OK/', $http_response, $regs)) {
                fclose($socket);
                return true;
            } else {
                return false;
            }
        }
    }
}
