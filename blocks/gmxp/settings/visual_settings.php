<?php

require(__DIR__.'/../../../config.php');
require_once($CFG->libdir.'/adminlib.php');

define('PLUGIN', 'block_gmxp');

admin_externalpage_setup( get_string('SYS_SETTINGS_VISUAL', PLUGIN) );

$form = new block_gmxp_visualsettingsform(); 
$output = '';

if ($form->is_cancelled()) {
    local_gamedlemaster_log::info('cancelled');
} /*else if ($form->is_submitted() && !$form->is_validated()) {

}*/ else if ($changes = $form->get_data()) {
    $form->submitChanges($changes);

} else {
    local_gamedlemaster_log::info('not validated');
}

echo $OUTPUT->header();
echo $form->render();
echo $OUTPUT->footer();
