<?php

/**
 * ---------------------------------------------------------------------------------------------------------------------
 * DESCRIPTION
 * ---------------------------------------------------------------------------------------------------------------------
 * This file contains the example of using realpath() with Filesystem.
 *
 * ---------------------------------------------------------------------------------------------------------------------
 * USAGE
 * ---------------------------------------------------------------------------------------------------------------------
 * To run this example in CLI from project root use following syntax
 *
 * $> php ./example/node_realpath.php
 *
 * Following flags are supported to test example with different configurations:
 *
 * --driver  : define driver to use, default: standard, supported: [ standard, eio ]
 * --invoker : define invoker to use, default: standard, supported: [ standard, queue ]
 *
 * Ex:
 * $> php ./example/node_realpath.php --driver=standard --invoker=standard
 *
 * ---------------------------------------------------------------------------------------------------------------------
 */

require_once __DIR__ . '/bootstrap/autoload.php';

use Dazzle\Filesystem\Filesystem;
use Dazzle\Loop\Model\SelectLoop;
use Dazzle\Loop\Loop;

$loop = new Loop(
    new SelectLoop()
);
$fsm = new Filesystem(
    new $driver($loop, [ 'root' => __DIR__ . '/data', 'invoker.class' => $invoker ])
);

$process = function() use($loop, $fsm) {
    $fsm
        ->realpath('/../data/_file_read.txt')
        ->then(function($result) {
            echo 'realpath is=' . $result . PHP_EOL;
        })
        ->failure(function($ex) {
            echo (string) $ex . PHP_EOL;
        })
        ->done(function() use($loop) {
            $loop->stop();
        });
};

$loop->onStart($process);
$loop->start();
