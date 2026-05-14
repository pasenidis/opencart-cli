<?php

/**
 * CLI Entry Point
 * @author Dionysis Pasenidis
 * @link https://github.com/pasenidis
 * @version 1.0
 */

// CLI mode only
if (PHP_SAPI !== 'cli') {
    header('Location: /');
    exit(1);
}

// Change directory to allow the script to be called from anywhere
chdir(__DIR__);

// Version
$index_contents = file_get_contents(__DIR__ . '/index.php');
preg_match('~define\s*\(\s*\'VERSION\'\s*,\s*\'(.*?)\'\s*\)\s*;~', $index_contents, $matches);

if (empty($matches[1])) {
    fwrite(STDERR, 'Cannot find OpenCart version.' . PHP_EOL);
    exit(1);
}

define('VERSION', $matches[1]);

$config_root = './';

if (!isset($_SERVER['SERVER_PORT'])) {
    $_SERVER['SERVER_PORT'] = 80;
}

// Configuration
if (is_file($config_root . 'config.php')) {
    require_once($config_root . 'config.php');
}

if (!defined('DIR_APPLICATION')) {
    fwrite(STDERR, 'OpenCart not installed.' . PHP_EOL);
    exit(1);
}

// Startup
require_once(DIR_SYSTEM . 'startup.php');

// Application
$application_config = 'cli';
require_once(DIR_SYSTEM . 'framework.php');
