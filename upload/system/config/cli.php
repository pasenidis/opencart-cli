<?php

/**
 * CLI Configuration
 * @author Dionysis Pasenidis
 * @link https://github.com/pasenidis
 * @version 1.0
 */

// Database
$_['db_autostart'] = true;
$_['db_engine'] = DB_DRIVER;
$_['db_hostname'] = DB_HOSTNAME;
$_['db_username'] = DB_USERNAME;
$_['db_password'] = DB_PASSWORD;
$_['db_database'] = DB_DATABASE;
$_['db_port'] = DB_PORT;

// Session
$_['session_autostart'] = false;

// URL
$_['url_autostart'] = false;

// Actions
$_['action_pre_action'] = [
    'cli/startup',
    'startup/startup',
    'startup/error',
    'startup/event',
];

$_['action_default'] = 'cli/not_found';
$_['action_router'] = 'cli/router';
$_['action_error'] = 'cli/not_found';
