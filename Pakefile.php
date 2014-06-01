<?php
pake_desc('Build phar archive');
pake_task('phar');

function run_phar()
{
    $build_dir = __DIR__ . '/build';

    pake_echo_action('phar', 'create/clean build dir');

    pake_mkdirs($build_dir);
    pake_remove(pakeFinder::type('file')->name('*'), $build_dir);

    pake_echo_action('phar', 'init phar archive');
    $phar_file = $build_dir . '/wp2md.phar';
    $phar = new Phar($phar_file);

    $phar->startBuffering();

    pake_echo_action('phar', 'determine and set version');
    $stub_file = file_get_contents(__DIR__ . '/bin/wp2md.php');
    $version   = trim(pake_sh('git describe --tags HEAD'));
    $stub_file = preg_replace('/@package_version@/', $version, $stub_file);

    $phar->addFromString('bin/wp2md.php', $stub_file);

    pake_echo_action('phar', 'add files');
    $phar->buildFromDirectory(__DIR__ . '/','@/(src|vendor)/@');

    pake_echo_action('phar', 'make stub executable');
    // Get the default stub
    $defaultStub = $phar->createDefaultStub('bin/wp2md.php');
    // Create a custom stub to add the shebang
    $stub = "#!/usr/bin/env php \n".$defaultStub;
    // Add the stub
    $phar->setStub($stub);

    $phar->stopBuffering();

    pake_chmod('wp2md.phar', $build_dir, 0755);

    pake_echo_action('phar', 'done. build/wp2md.phar is created');
}
