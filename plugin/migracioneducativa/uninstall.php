<?php
/* For license terms, see /license.txt */

/**
 * This script is included by main/admin/settings.lib.php when unselecting a plugin
 * and is meant to remove things installed by the install.php script in both
 * the global database and the courses tables.
 *
 * @package chamilo.ava
 */
/**
 * Queries.
 */
require_once __DIR__.'/config.php';

policy::init()->onlyAdmin();

MigracionEducativaPlugin::create()->uninstall();
