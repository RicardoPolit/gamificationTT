<?php
/*
 * UNIVERSITY:  INSTITUTO POLITECNICO NACIONAL (IPN)
 *              ESCUELA SUPERIOR DE COMPUTO (ESCOM)
 *
 *   PROJECT:     Gamedle
 *   PROFESSOR:   Sandra Bautista, Andres Catalan.
 *
 * DESARROLLADORES: Daniel Ortega (GitLab/Github @DanielOrtegaZ)
*/

require(__DIR__.'/../../../config.php');
require_once($CFG->libdir.'/adminlib.php');


$PLUGIN = 'block_gmcs';
admin_externalpage_setup(block_gmcs_core::SETTINGS_SCHEME);

$CONTENT = '';
$form = new block_gmcs_schemesettingsform();
$currency_activated = get_config($PLUGIN, block_gmcs_core::ACTIVATED);

/**
 * Si el módulo financiero se encuentra activado
 * procede de forma natural al formulario de configuraciones
 */ 

if($currency_activated){

    if ($form->is_cancelled()) {
        $form->go_site_administration();

    } else if ($changes = $form->get_data()) {
        $form->submit_changes($changes);

    } else {
        local_gamedlemaster_log::info('not validated');
    }

    $CONTENT = $form->render();

} else {

    $category = block_gmcs_core::CATEGORY;
    $error =    get_string('SETTINGS_CURRENCY_DISABLED', $PLUGIN);
    $text =     get_string('SETTINGS_CURRENCY_DISABLED_REDIRECT', $PLUGIN);
    $link = "{$CFG->wwwroot}/admin/category.php?category={$category}";

    $CONTENT = $form->render_problem($error, $text, $link);
}

echo $OUTPUT->header();
echo $CONTENT;
echo $OUTPUT->footer();
?>
