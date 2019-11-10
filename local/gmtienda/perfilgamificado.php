<?php

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');

require_once('../../config.php');

$course     = $DB->get_record('course', array('id' => 1), '*', MUST_EXIST);
$title = get_string('perfilgamificado', 'gmtienda');
$pagetitle = $title;
$PAGE->set_context(context_system::instance());
$PAGE->set_url('/local/gmtienda/perfilgamificado.php');
require_login($course,true);
$PAGE->set_title('Perfil gamificado');
$PAGE->set_heading('Perfil gamificado');
$renderer = $PAGE->get_renderer('local_gmtienda');

global $DB;

$usuariogamificado = $DB->get_record('gmdl_usuario',array('mdl_id_usuario' => $USER->id));

echo $OUTPUT->header();

echo $renderer->render_main_page($usuariogamificado);

echo $OUTPUT->footer();
