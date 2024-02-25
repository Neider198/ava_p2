<?php
/* For licensing terms, see /license.txt */
/**
 * Update the plugin.
 *
 * @package chamilo.ava
 */
require_once __DIR__.'/config.php';

policy::init()->onlyAdmin();

MigracionEducativaPlugin::create()->update();
