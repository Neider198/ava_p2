

<?php

require_once __DIR__.'/../../main/inc/global.inc.php';
require_once __DIR__ . '/src/migracion_educativa_plugin.class.php';

$plugin = MigracionEducativaPlugin::create();
$tool_name = get_lang('MIGRACION EDUCATIVA ');
$tpl = new Template($tool_name);
$content = $tpl->fetch('migracioneducativa/view/admin.tpl');
$tpl->assign('content', $content);
$tpl->display_one_col_template();
