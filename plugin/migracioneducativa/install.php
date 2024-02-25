<?php
/* For license terms, see /license.txt */
/**
 * This script is included by main/admin/settings.lib.php and generally
 * includes things to execute in the main database (settings_current table).
 *
 * @package chamilo.ava
 */
/**
 * Initialization.
 */
require_once __DIR__.'/config.php';


policy::init()->onlyAdmin();

MigracionEducativaPlugin::create()->install();

