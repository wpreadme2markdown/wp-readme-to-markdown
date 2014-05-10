<?php

require __DIR__ . '/../vendor/autoload.php';

$application = new \Symfony\Component\Console\Application();
$application->add(new \SunChaser\WP2MD\cli\Convert());
$application->run();
