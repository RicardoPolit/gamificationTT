<?php

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
require_once($CFG->dirroot . '/mod/gmcompcpu/locallib.php');

$userid = optional_param('userid', 0, PARAM_INT);
$instance  = optional_param('instance', 0, PARAM_INT);
$dificultad  = optional_param('dificultad', 0, PARAM_INT);
$gmuserid  = optional_param('gmuserid', 0, PARAM_INT);

$gmcompcpu  = $DB->get_record('gmcompcpu', array('id' => $instance), '*', MUST_EXIST);
$course     = $DB->get_record('course', array('id' => $gmcompcpu->course), '*', MUST_EXIST);
$cm         = get_coursemodule_from_instance('gmcompcpu', $gmcompcpu->id, $course->id, false, MUST_EXIST);


require_login($course, true, $cm);

$context = context_module::instance($cm->id);

$PAGE->set_url('/mod/gmcompcpu/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($gmcompcpu->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($context);

$renderer = $PAGE->get_renderer('mod_gmcompcpu');

echo $OUTPUT->header();

echo $OUTPUT->heading_with_help(get_string('modulename', 'mod_gmcompcpu'), 'howtoplay', 'mod_gmcompcpu');

echo "<link href='https://fonts.googleapis.com/css?family=Audiowide' rel='stylesheet' type='text/css'>";

echo $renderer->render_questions($gmcompcpu,$cm,$USER->id,$dificultad,$gmuserid);

echo $OUTPUT->footer();

/*echo $userid;
echo '<br>';
echo $instance;
echo '<br>';
echo $dificultad;*/