<?php

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');

require_once('../../config.php');

$course     = $DB->get_record('course', array('id' => 1), '*', MUST_EXIST);
$title = get_string('perfilgamificado', 'local_gmtienda');
$pagetitle = $title;
$PAGE->set_context(context_system::instance());
$PAGE->set_url('/local/gmtienda/perfilgamificado.php');
require_login($course,true);
$PAGE->set_title($title);
$PAGE->set_heading($title);
$renderer = $PAGE->get_renderer('local_gmtienda');

global $DB;

$usuariogamificado = obtener_usuario_gamedle($USER->id);
if(is_null($usuariogamificado))
    {
        $usuariogamificado = insertar_usuario($USER->id);
    }

echo $OUTPUT->header();

echo $renderer->render_main_page($usuariogamificado, $USER->id);

echo $OUTPUT->footer();



function obtener_usuario_gamedle($moodleUserId)
    {
        global $DB;
        return $DB->get_record('gmdl_usuario', $conditions=array("mdl_id_usuario" => $moodleUserId), $fields='*', $strictness=IGNORE_MISSING)->id;
    }

function insertar_usuario($usuario)
    {
        global $DB;
        $data = new stdClass();
        $data->mdl_id_usuario = $usuario;
        $data->nivel_actual = 1;
        $data->experiencia_actual = 0;
        return $DB->insert_record('gmdl_usuario', $data, $returnid=true, $bulk=false);
    }