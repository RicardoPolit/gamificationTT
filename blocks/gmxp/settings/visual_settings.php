<?php

require(__DIR__.'/../../../config.php');
require_once($CFG->libdir.'/adminlib.php');

$PLUGIN = 'block_gmxp';
$MESSAGE = '';
$CONTENT = '';
$experience_activated = get_config($PLUGIN,
  get_string('SYS_SETTINGS_GENERAL_ACTIVATED', $PLUGIN));


/**
 * Si el módulo de experiencia se encuentra activado
 * procede de forma natural al formulario de configuraciones
 */ 
admin_externalpage_setup( get_string('SYS_SETTINGS_VISUAL', $PLUGIN) );
if ($experience_activated) {

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

    $CONTENT = $form->render();

/**
 * Si el módulo de experiencia no se encuentra activado
 * entonces se imprime la pantalla de error
 *
 * Docs https://docs.moodle.org/dev/html_writer
 */ 
} else {

    $MESSAGE = $OUTPUT->notification(
                 get_string('SETTINGS_EXPERIENCE_DISABLED', $PLUGIN));

    $text = get_string('SETTINGS_EXPERIENCE_DISABLED_REDIRECT', $PLUGIN);
    $category = get_string('SYS_SETTINGS_CATEGORY', $PLUGIN);

    $CONTENT = html_writer::start_tag('a', array(
        'href' => "{$CFG->wwwroot}/admin/category.php?category={$category}"
    ));

    $CONTENT .= html_writer::empty_tag('input', array(
        'class' => "btn btn-primary",
        'value' => $text,
        'style' => 'margin:auto; display: block'
    ));

    $CONTENT .= html_writer::end_tag('a');

}

echo $OUTPUT->header();
echo $MESSAGE;
echo $CONTENT;
echo $OUTPUT->footer();
