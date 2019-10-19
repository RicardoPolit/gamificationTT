<?php

require(__DIR__.'/../../../config.php');
require_once($CFG->libdir.'/adminlib.php');

define('PLUGIN', 'block_gmxp');

admin_externalpage_setup( get_string('SYS_SETTINGS_VISUAL', PLUGIN) );

$form = new block_gmxp_visualsettingsform(); 

echo $OUTPUT->header();
echo $form->render();
echo $OUTPUT->footer();
