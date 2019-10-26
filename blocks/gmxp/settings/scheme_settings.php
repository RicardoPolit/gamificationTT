<?php

require(__DIR__.'/../../../config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->dirroot . '/blocks/gmxp/includes/colourpicker.php');

$PLUGIN = 'block_gmxp';
$experience_activated = true;

admin_externalpage_setup(get_string('SYS_SETTINGS_SCHEME', $PLUGIN));

if($experience_activated){

    $form = new block_gmxp_schemesettingsform();
    $CONTENT = $form->render();

} else {

    local_gamedlemaster_log::warning('Implement the else', 'TODO');
}

echo $OUTPUT->header();
echo $MESSAGE;
echo $CONTENT;
echo $OUTPUT->footer();
