<?php
pake_desc('Build phar archive');
pake_task('phar');

function exec_composer($path)
{
    $prev_dir = getcwd();
    chdir($path);

    $composer = `which composer`;

    if (empty($composer)) {
        // official composer installation guide from https://getcomposer.org/download/
        copy('https://getcomposer.org/installer', 'composer-setup.php');
        if (hash_file('SHA384', 'composer-setup.php') === 'e115a8dc7871f15d853148a7fbac7da27d6c0030b848d9b3dc09e2a0388afed865e6a3d6b3c0fad45c48e2b5fc1196ae') {
            echo 'Installer verified';
        } else {
            echo 'Installer corrupt';
        }
        echo PHP_EOL;
        pake_sh('/usr/bin/env php composer-setup.php');
        unlink('composer-setup.php');

        $composer = 'php composer.phar';
    }

    $composer = trim($composer);

    pake_sh("$composer update --no-dev -o --prefer-lowest");

    chdir($prev_dir);
}

function run_phar()
{
    $build_dir = __DIR__ . '/build';

    pake_echo_action('phar', 'prepare build dir');

    pake_mkdirs($build_dir);
    pake_remove(pakeFinder::type('file')->name('*'), $build_dir);

    // project files
    pake_mirror(pakeFinder::type('file')->name('*.php'), __DIR__ . '/bin', $build_dir . '/bin');
    pake_mirror(pakeFinder::type('file')->name('*.php'), __DIR__ . '/src', $build_dir . '/src');

    // make clean library installation without dev dependencies
    pake_copy(__DIR__ . '/composer.json', $build_dir . '/composer.json');
    exec_composer($build_dir);

    pake_echo_action('phar', 'set product version');

    $bin_file   = file_get_contents($build_dir . '/bin/wp2md.php');
    $version    = trim(pake_sh('git describe --tags HEAD'));
    $bin_file   = preg_replace('/@package_version@/', $version, $bin_file);
    file_put_contents($build_dir . '/bin/wp2md.php', $bin_file);

    pake_echo_action('phar', 'init phar archive');

    $phar = new \Secondtruth\Compiler\Compiler($build_dir);

    $phar->addDirectory('bin');
    $phar->addDirectory('src');
    $phar->addDirectory('vendor', array('!*.php', '*Test.php', '*Tester.php', '*/Tests/*'));

    $phar->addIndexFile('bin/wp2md.php', 'cli');

    $phar->compile($build_dir . '/wp2md.phar');

    pake_chmod('wp2md.phar', $build_dir, 0755);

    pake_echo_action('phar', 'done. build/wp2md.phar is created');
}
