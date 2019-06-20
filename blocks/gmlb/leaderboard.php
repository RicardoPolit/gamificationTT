<?php
/*
 * UNIVERSITY:  INSTITUTO POLITECNICO NACIONAL (IPN)
 *              ESCUELA SUPERIOR DE COMPUTO (ESCOM)
 *   SUBJECT:     _
 *   PROFESSOR:   _
 *
 * DESARROLLADORES: Daniel Ortega
 *
 * PRACTICE _:  TITULO DE LA PRACTICA
 *               - DESCRIPCION
 *		
*/

require(__DIR__.'/../../config.php');
require(__DIR__.'/classes/gmlb_table.php');

// https://docs.moodle.org/dev/Page_API#A_simple_example

    if(!isset($_GET['course']))
        $_GET['course'] = 1;

    $course = $DB->get_record('course', array('id' => $_GET['course']));
    $context = context_course::instance($course->id);

    $PAGE->set_url(new moodle_url("/blocks/gmlb/leaderboard.php"));
    $PAGE->set_context($context);
    $PAGE->set_course($course);

    $PAGE->set_title("Leaderboards");
    $PAGE->set_heading("Leaderboards");
    $PAGE->set_button("Go to Leaderboards");
    $PAGE->set_headingmenu("holamundo");

    /**
     *  Using PAGE->navbar->add() hides the settings gear icon
     */
    $PAGE->navbar->add('Leaderboard', new moodle_url("/blocks/gmlb/leaderboard.php"));

    echo $OUTPUT->header();

        $table = new gmlb_table();
        $table->out(40, true);

    echo $OUTPUT->footer();

?>
