<?php

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');

$userid = optional_param('userid', 0, PARAM_INT);
$instance  = optional_param('instance', 0, PARAM_INT);
$rivalid  = optional_param('rivalid', -1, PARAM_INT);
$participacionid  = optional_param('participacionid', -1, PARAM_INT);
$gmuserid  = optional_param('gmuserid', 0, PARAM_INT);
$id = optional_param('id', 0, PARAM_INT);
$montoapuesta = optional_param('montoapuesta', -1, PARAM_INT);

$gmcompvs  = $DB->get_record('gmcompvs', array('id' => $instance), '*', MUST_EXIST);
$course     = $DB->get_record('course', array('id' => $gmcompvs->course), '*', MUST_EXIST);
$cm         = get_coursemodule_from_instance('gmcompvs', $gmcompvs->id, $course->id, false, MUST_EXIST);


require_login($course, true, $cm);

if($rivalid != -1 && $participacionid == -1){

    $flag =

    $partidaid = $DB->insert_record('gmdl_partida',(object)array('gmdl_comp_vs_id' => $instance));

    $participacionvalues = (object)array(
        'gmdl_usuario_id' => $gmuserid,
        'gmdl_partida_id' => $partidaid
    );

    $participacionid = $DB->insert_record('gmdl_participacion',$participacionvalues);

    $participacionvalues = (object)array(
        'gmdl_usuario_id' => $rivalid,
        'gmdl_partida_id' => $partidaid
    );

    $DB->insert_record('gmdl_participacion',$participacionvalues);

}

$context = context_module::instance($cm->id);


$PAGE->set_title(format_string($gmcompvs->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($context);

$renderer = $PAGE->get_renderer('mod_gmcompvs');

echo $OUTPUT->header();

echo $OUTPUT->heading($gmcompvs->name);

echo "<link href='https://fonts.googleapis.com/css?family=Audiowide' rel='stylesheet' type='text/css'>";

$montoapuesta = 50;

echo $renderer->render_questions($gmcompvs,$cm,$USER->id,$participacionid,$gmuserid,$id,$montoapuesta);

echo $OUTPUT->footer();

/*echo $userid;
echo '<br>';
echo $instance;
echo '<br>';
echo $dificultad;*/