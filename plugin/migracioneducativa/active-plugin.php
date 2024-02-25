<?php
/**
 *
 * @package chamilo.ava
 */
/**
 *  .
 */
require_once __DIR__.'/config.php';

policy::init()->onlyAdmin();

$plugin = new AppPlugin();

$plugin->install(basename(__DIR__));

header("Location: /");
