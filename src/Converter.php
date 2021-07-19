<?php

declare(strict_types=1);

namespace WPReadme2Markdown;

use GuzzleHttp\Client;

/**
 * Converts WordPress-flavored markup from standard readme.txt files
 * to Github-flavored markup for a README.md file
 * @author Benjamin J. Balter -- http://ben.balter.com
 * @license MIT
 * @version 1.0
 */
final class Converter
{
    /** @var Client */
    private static $client = null;

    private function __construct() {}

    /**
     * @param string $readme plugin readme.txt content
     * @param string|null $pluginSlug explicitly set the plugin slug, NULL for autodetect
     * @return string
     */
    public static function convert(string $readme, ?string $pluginSlug = null): string
    {
        $readme = self::normalizeLineEndings($readme);
        $readme = self::convertHeadings($readme);
        $readme = self::convertLabels($readme);
        $readme = self::convertScreenshots($readme, $pluginSlug);

        return trim($readme) . "\n";
    }

    private static function normalizeLineEndings(string $readme): string
    {
        // normalize line endings to Unix
        return preg_replace("|\R|u", "\n", $readme);
    }

    private static function convertHeadings(string $readme): string
    {
        // Convert Headings
        // original code from https://github.com/markjaquith/WordPress-Plugin-Readme-Parser/blob/master/parse-readme.php
        // using here in reverse to go from WP to GitHub style headings
        $readme = preg_replace('|\n*===\s*([^=]+?)\s*=*\s*\n+|im', PHP_EOL . "\n# $1\n" . PHP_EOL, $readme);
        $readme = preg_replace('|\n*==\s*([^=]+?)\s*=*\s*\n+|im', PHP_EOL . "\n## $1\n" . PHP_EOL, $readme);
        $readme = preg_replace('|\n*=\s*([^=]+?)\s*=*\s*\n+|im', PHP_EOL . "\n### $1\n" . PHP_EOL, $readme);

        return $readme;
    }

    private static function generateUniquePattern(string $readme): string
    {
        for ($i = 0; $i < 5; $i++) {
            $uniquePattern = uniqid('@#:');

            if (strpos($readme, $uniquePattern) === false) {
                return $uniquePattern;
            }
        }

        throw new \RuntimeException('Something wrong with randomness');
    }

    private static function convertLabels(string $readme): string
    {
        $uniquePattern = self::generateUniquePattern($readme);

        //parse contributors, donate link, etc.
        $labels = [
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
        ];
        foreach ($labels as $label) {
            $readme = preg_replace("|^($label): (.+)$|im", $uniquePattern . '**$1:** $2' . $uniquePattern, $readme);
        }

        // replace all middle occurrences with <br>
        $readme = str_replace($uniquePattern . "\n" . $uniquePattern, " \\\n", $readme);
        // remove start and end markers
        $readme = str_replace($uniquePattern, '', $readme);

        return $readme;
    }

    private static function getPluginSlug(string $readme, ?string $pluginSlug): string
    {
        if ($pluginSlug !== null) {
            return $pluginSlug;
        }

        // guess plugin slug from plugin name
        preg_match('|^#(.*?)$|im', $readme, $matches);
        return str_replace(' ', '-', strtolower(trim($matches[1])));
    }

    private static function convertScreenshots(string $readme, ?string $pluginSlug): string
    {
        $plugin = self::getPluginSlug($readme, $pluginSlug);

        //process screenshots, if any
        if (preg_match('|## Screenshots\n(.*?)\n## [a-z]+|ism', $readme, $matches)) {
            //parse screenshot list into array
            preg_match_all('|^[0-9]+\. (.*)$|im', $matches[1], $screenshots, PREG_SET_ORDER);

            //replace list item with markdown image syntax, hotlinking to plugin repo
            $i = 1;
            $lastPrefix = $lastExtension = null;
            foreach ($screenshots as $screenshot) {
                [$screenshotUrl, $lastPrefix, $lastExtension] = self::findScreenshot($i, $plugin, $lastPrefix, $lastExtension);
                if ($screenshotUrl) {
                    $readme = str_replace($screenshot[0], "### {$i}. {$screenshot[1]}\n\n![{$screenshot[1]}](" . $screenshotUrl . ")\n", $readme);
                } else {
                    $readme = str_replace($screenshot[0], "### {$i}. {$screenshot[1]}\n\n[missing image]\n", $readme);
                }
                $i++;
            }
        }

        return $readme;
    }

    /**
     * Finds the correct screenshot file with the given number and plugin slug.
     *
     * As per the WordPress plugin repo, file extensions may be any
     * of: (png|jpg|jpeg|gif).  We look in the /assets directory first,
     * then in the base directory.
     *
     * @param int $number Screenshot number to look for
     * @param string $pluginSlug
     * @param string|null $lastPrefix
     * @param string|null $lastExtension
     * @return array|false   Valid screenshot URL + optimization data or false if none found
     * @uses url_validate
     * @link http://wordpress.org/plugins/about/readme.txt
     */
    private static function findScreenshot(
        int $number,
        string $pluginSlug,
        ?string $lastPrefix,
        ?string $lastExtension
    ) {
        $extensions = ['png', 'jpg', 'jpeg', 'gif'];

        // this seems to now be the correct URL, not s.wordpress.org/plugins
        $baseUrl   = 'https://s.w.org/plugins/' . $pluginSlug . '/';
        $assetsUrl = 'https://ps.w.org/' . $pluginSlug . '/assets/';

        $prefixes = [$assetsUrl, $baseUrl];

        if ($lastPrefix) {
            $prefixes = array_unique(array_merge([$lastPrefix], $prefixes));
        }

        if ($lastExtension) {
            $extensions = array_unique(array_merge([$lastExtension], $extensions));
        }

        /* check assets for all extensions first, because if there's a
           gif in the assets directory and a jpg in the base directory,
           the one in the assets directory needs to win.
        */
        foreach ($prefixes as $prefixUrl) {
            foreach ($extensions as $ext) {
                $url = $prefixUrl . 'screenshot-' . $number . '.' . $ext;
                if (self::validateUrl($url)) {
                    return [$url, $prefixUrl, $ext];
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
    private static function validateUrl(string $link): bool
    {
        if (self::$client ===  null) {
            self::$client = new Client(['http_errors' => false]);
        }

        $response = self::$client->request('HEAD', $link);

        return $response->getStatusCode() === 200;
    }
}
