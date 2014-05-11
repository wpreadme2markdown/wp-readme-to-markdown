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
$phar->setDefaultStub('bin/wp2md.php');

// add files
$phar->buildFromDirectory(__DIR__ . '/../','@/(bin|src|vendor)/@');
