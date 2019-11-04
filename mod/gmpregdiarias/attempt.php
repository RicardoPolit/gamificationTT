<?php

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');

$userid = optional_param('userid', 0, PARAM_INT);
$instance  = optional_param('instance', 0, PARAM_INT);
$gmuserid  = optional_param('gmuserid', 0, PARAM_INT);
$id = optional_param('id', 0, PARAM_INT);

$gmpregdiarias  = $DB->get_record('gmpregdiarias', array('id' => $instance), '*', MUST_EXIST);
$course     = $DB->get_record('course', array('id' => $gmpregdiarias->course), '*', MUST_EXIST);
$cm         = get_coursemodule_from_instance('gmpregdiarias', $gmpregdiarias->id, $course->id, false, MUST_EXIST);


require_login($course, true, $cm);

$context = context_module::instance($cm->id);


$PAGE->set_title(format_string($gmpregdiarias->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($context);

$renderer = $PAGE->get_renderer('mod_gmpregdiarias');

echo $OUTPUT->header();

echo $OUTPUT->heading($gmpregdiarias->name);

echo "<link href='https://fonts.googleapis.com/css?family=Audiowide' rel='stylesheet' type='text/css'>";

echo $renderer->render_questions($gmpregdiarias,$cm,$USER->id,$gmuserid,$id);

echo $OUTPUT->footer();

/*echo $userid;
echo '<br>';
echo $instance;
echo '<br>';
echo $dificultad;*/