#!/usr/bin/env php
<?php
/**
 * @author Christian Archer <chrstnarchr@aol.com>
 * @copyright © 2014, Christian Archer
 * @license MIT
 */

if (is_file(__DIR__ . '/wp2md.phar')) {
    unlink(__DIR__ . '/wp2md.phar');
}

// init phar archive
$phar = new Phar(__DIR__ . '/wp2md.phar');

// run cli
$stub_file = file_get_contents(__DIR__.'/../bin/wp2md.php');
$version   = trim(`git describe --tags HEAD`);
$stub_file = preg_replace('/@package_version@/', $version, $stub_file);

$phar->addFromString('bin/wp2md.php', $stub_file);
$phar->setDefaultStub('bin/wp2md.php');

// add files
$phar->buildFromDirectory(__DIR__ . '/../','@/(src|vendor)/@');
