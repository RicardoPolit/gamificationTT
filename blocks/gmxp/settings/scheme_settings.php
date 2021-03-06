<?php

require(__DIR__.'/../../../config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->dirroot . '/blocks/gmxp/includes/colourpicker.php');

$PLUGIN = 'block_gmxp';
admin_externalpage_setup(block_gmxp_core::SETTINGS_SCHEME);

$CONTENT = '';
$form = new block_gmxp_schemesettingsform();
$experience_activated = get_config($PLUGIN, block_gmxp_core::ACTIVATED);

/**
 * Si el módulo de experiencia se encuentra activado
 * procede de forma natural al formulario de configuraciones
 */ 
if($experience_activated){

    if ($form->is_cancelled()) {
        $form->go_site_administration();

    } else if ($changes = $form->get_data()) {
        $form->submit_changes($changes);

    } else {
        local_gamedlemaster_log::info('not validated');
    }

    $CONTENT = $form->render();

} else {

    $category = block_gmxp_core::CATEGORY;
    $error =    get_string('SETTINGS_EXPERIENCE_DISABLED', $PLUGIN);
    $text =     get_string('SETTINGS_EXPERIENCE_DISABLED_REDIRECT', $PLUGIN);
    $link = "{$CFG->wwwroot}/admin/category.php?category={$category}";

    $CONTENT = $form->render_problem($error, $text, $link);
}

echo $OUTPUT->header();
echo $CONTENT;
echo $OUTPUT->footer();
