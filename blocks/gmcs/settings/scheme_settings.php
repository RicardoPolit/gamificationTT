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

$CONTENT = 'HOLA MUNDO';
/*
$form = new block_gmxp_schemesettingsform();
$experience_activated = get_config($PLUGIN, block_gmxp_core::ACTIVATED);

/**
 * Si el módulo de experiencia se encuentra activado
 * procede de forma natural al formulario de configuraciones
 */ 

/*
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
}*/

echo $OUTPUT->header();
echo $CONTENT;
echo $OUTPUT->footer();
?>
