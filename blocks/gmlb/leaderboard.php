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

// https://docs.moodle.org/dev/Page_API#A_simple_example

    $course = $DB->get_record('course', array('id' => 50));
    $context = context_course::instance($course->id);

    $PAGE->set_url(new moodle_url("/blocks/gmlb/leaderboard.php"));
    $PAGE->set_context($context);
    $PAGE->set_course($course);

    $PAGE->set_title("Leaderboards");
    $PAGE->set_heading("Leaderboards");
    $PAGE->set_button("Go to Leaderboards");

    echo $OUTPUT->header();
    echo "<h1>";
    
        echo "HERE YOU MUST PRINT THE TABLE<br>";
        echo "HERE YOU MUST PRINT THE TABLE<br>";
        echo "HERE YOU MUST PRINT THE TABLE<br>";
        echo "HERE YOU MUST PRINT THE TABLE<br>";
        echo "HERE YOU MUST PRINT THE TABLE<br>";
        echo "HERE YOU MUST PRINT THE TABLE<br>";
        echo "HERE YOU MUST PRINT THE TABLE<br>";
        echo "HERE YOU MUST PRINT THE TABLE<br>";
        
    echo "</h1>";
    echo $OUTPUT->footer();

?>
