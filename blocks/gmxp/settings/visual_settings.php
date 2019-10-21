<?php

require(__DIR__.'/../../../config.php');
require_once($CFG->libdir.'/adminlib.php');

$PLUGIN = 'block_gmxp';
$MESSAGE = '';
admin_externalpage_setup( get_string('SYS_SETTINGS_VISUAL', $PLUGIN) );

    $form = new block_gmxp_visualsettingsform(); 

    if ($form->is_cancelled()) {
        local_gamedlemaster_log::info('cancelled');

    } else if ($changes = $form->get_data()) {

        $form->submit_changes($changes);
    
        if ($form->file_error) {
            $MESSAGE = $OUTPUT->notification( $form->error );

        } else {
            $MESSAGE = $OUTPUT->notification(get_string('success', $PLUGIN),
              'notifysuccess');
        }

    } else {
        local_gamedlemaster_log::info('not validated');
    }

echo $OUTPUT->header();
echo $MESSAGE;
echo $form->render();
echo $OUTPUT->footer();
