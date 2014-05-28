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
$phar_file = __DIR__ . '/wp2md.phar';
$phar = new Phar($phar_file);

$phar->startBuffering();

// run cli
$stub_file = file_get_contents(__DIR__.'/../bin/wp2md.php');
$version   = trim(`git describe --tags HEAD`);
$stub_file = preg_replace('/@package_version@/', $version, $stub_file);

$phar->addFromString('bin/wp2md.php', $stub_file);

// add files
$phar->buildFromDirectory(__DIR__ . '/../','@/(src|vendor)/@');

// Get the default stub. You can create your own if you have specific needs
$defaultStub = $phar->createDefaultStub('bin/wp2md.php');

// Create a custom stub to add the shebang
$stub = "#!/usr/bin/env php \n".$defaultStub;
// Add the stub
$phar->setStub($stub);

$phar->stopBuffering();

$phar_file = escapeshellarg($phar_file);
print `chmod a+x $phar_file`;
